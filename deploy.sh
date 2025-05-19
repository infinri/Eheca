#!/bin/bash
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}Starting deployment...${NC}"

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    echo -e "${YELLOW}Warning: Running as root is not recommended. Please run as a non-root user with sudo privileges.${NC}"
fi

# Load environment variables
if [ -f .env.prod ]; then
    echo -e "${GREEN}Loading production environment...${NC}"
    if [ ! -f .env ]; then
        cp .env.prod .env
        echo -e "${GREEN}Created .env file from .env.prod${NC}"
    else
        echo -e "${YELLOW}.env file already exists, not overwriting${NC}"
    fi
    
    # Export variables from .env file
    export $(grep -v '^#' .env | xargs)
else
    echo -e "${RED}Error: .env.prod file not found!${NC}"
    exit 1
fi

# Install dependencies
echo -e "${GREEN}Installing PHP dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-suggest

# Install Node.js dependencies if package.json exists
if [ -f package.json ]; then
    echo -e "${GREEN}Installing Node.js dependencies...${NC}"
    npm install --production
    
    # Build assets if needed
    if [ -f webpack.config.js ] || [ -f webpack.config.ts ] || [ -f webpack.mix.js ]; then
        echo -e "${GREEN}Building assets...${NC}"
        npm run build
    fi
fi

# Set up directories with proper permissions
echo -e "${GREEN}Setting up directories...${NC}"
mkdir -p var/cache var/log public/uploads

# Clear and warmup cache
echo -e "${GREEN}Clearing and warming up cache...${NC}
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear --no-warmup
APP_ENV=prod APP_DEBUG=0 php bin/console cache:warmup

# Run database migrations if Doctrine is installed
if [ -f bin/console ] && php bin/console | grep -q "doctrine:migrations:migrate"; then
    echo -e "${GREEN}Running database migrations...${NC}"
    php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
fi

# Install assets if needed
if [ -f bin/console ] && php bin/console | grep -q "assets:install"; then
    echo -e "${GREEN}Installing assets...${NC}"
    php bin/console assets:install --symlink --relative public
fi

# Set proper permissions
echo -e "${GREEN}Setting file permissions...${NC}"
# Set owner to www-data (or your web server user)
if [ "$EUID" -eq 0 ]; then
    chown -R www-data:www-data var public/uploads
    find var -type d -exec chmod 775 {} \;
    find var -type f -exec chmod 664 {} \;
    find public/uploads -type d -exec chmod 775 {} \;
    find public/uploads -type f -exec chmod 664 {} \;
else
    chmod -R 775 var public/uploads
fi

# Clear opcache if PHP-FPM is running
if systemctl is-active --quiet php*-fpm.service 2>/dev/null; then
    echo -e "${GREEN}Restarting PHP-FPM...${NC}"
    sudo systemctl reload php*-fpm.service
fi

# Restart web server if needed
if systemctl is-active --quiet nginx 2>/dev/null; then
    echo -e "${GREEN}Testing nginx configuration...${NC}"
    sudo nginx -t
    echo -e "${GREEN}Reloading nginx...${NC}"
    sudo systemctl reload nginx
fi

# Set maintenance mode off (if you have a custom command for this)
if [ -f bin/console ] && php bin/console | grep -q "maintenance:disable"; then
    php bin/console maintenance:disable
fi

# Run health check
if [ -f bin/console ] && php bin/console | grep -q "health:check"; then
    echo -e "${GREEN}Running health checks...${NC}"
    php bin/console health:check
fi

echo -e "\n${GREEN}✅ Deployment completed successfully at $(date)${NC}"
