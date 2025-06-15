#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to display help
show_help() {
    echo -e "${YELLOW}Eheca Development Script${NC}"
    echo -e "\n${GREEN}Usage:${NC} ./bootstrap.sh [command]\n"
    echo -e "Available commands:"
    echo -e "  ${YELLOW}setup${NC}       - Install PHP and Node.js dependencies"
    echo -e "  ${YELLOW}dev${NC}         - Start the development server"
    echo -e "  ${YELLOW}build${NC}       - Build frontend assets"
    echo -e "  ${YELLOW}watch${NC}       - Watch for changes and rebuild assets"
    echo -e "  ${YELLOW}test${NC}        - Run tests"
    echo -e "  ${YELLOW}lint${NC}        - Run linters"
    echo -e "  ${YELLOW}fix${NC}         - Fix linting issues"
    echo -e "  ${YELLOW}clean${NC}       - Remove generated files"
    echo -e "  ${YELLOW}help${NC}        - Show this help message\n"
    echo -e "Example: ${YELLOW}./bootstrap.sh setup${NC}"
}

# Function to run setup
run_setup() {
    echo -e "${GREEN}🚀 Setting up Eheca...${NC}"
    
    # Check for PHP
    if ! command -v php &> /dev/null; then
        echo -e "${RED}❌ PHP is not installed. Please install PHP 8.1 or higher.${NC}"
        exit 1
    fi
    
    # Check for Composer
    if ! command -v composer &> /dev/null; then
        echo -e "${RED}❌ Composer is not installed. Please install Composer.${NC}"
        exit 1
    fi
    
    # Check for Node.js and npm
    if ! command -v node &> /dev/null || ! command -v npm &> /dev/null; then
        echo -e "${RED}❌ Node.js and npm are required. Please install Node.js 16+.${NC}"
        exit 1
    fi
    
    echo -e "${GREEN}✓ System requirements check passed${NC}"
    
    # Install PHP dependencies
    echo -e "\n${YELLOW}Installing PHP dependencies...${NC}"
    composer install --no-interaction --optimize-autoloader
    
    # Install Node.js dependencies
    echo -e "\n${YELLOW}Installing Node.js dependencies...${NC}"
    npm install
    
    # Build assets
    echo -e "\n${YELLOW}Building frontend assets...${NC}"
    npm run build
    
    # Set file permissions
    echo -e "\n${YELLOW}Setting file permissions...${NC}"
    chmod -R 755 storage/ bootstrap/cache/
    
    # Create .env if it doesn't exist
    if [ ! -f ".env" ]; then
        echo -e "\n${YELLOW}Creating .env file...${NC}"
        cp .env.example .env
        php artisan key:generate
    fi
    
    echo -e "\n${GREEN}✅ Setup complete! Run ${YELLOW}./bootstrap.sh dev${GREEN} to start the development server.${NC}"
}

# Function to start development server
run_dev() {
    echo -e "${GREEN}🚀 Starting development server...${NC}"
    echo -e "${YELLOW}Press Ctrl+C to stop${NC}"
    php -S localhost:8000 -t public
}

# Function to build assets
run_build() {
    echo -e "${GREEN}🛠️  Building assets...${NC}"
    npm run build
}

# Function to watch for changes
run_watch() {
    echo -e "${GREEN}👀 Watching for changes...${NC}"
    npm run watch
}

# Function to run tests
run_tests() {
    echo -e "${GREEN}🧪 Running tests...${NC}"
    if [ -f "vendor/bin/phpunit" ]; then
        ./vendor/bin/phpunit
    else
        echo -e "${YELLOW}PHPUnit is not installed. Running composer install...${NC}"
        composer install --dev
        ./vendor/bin/phpunit
    fi
}

# Function to run linters
run_lint() {
    echo -e "${GREEN}🔍 Running linters...${NC}"
    
    # PHP_CodeSniffer
    if [ -f "vendor/bin/phpcs" ]; then
        echo -e "\n${YELLOW}Running PHP_CodeSniffer...${NC}"
        ./vendor/bin/phpcs --standard=PSR12 app/ tests/
    else
        echo -e "${YELLOW}PHP_CodeSniffer is not installed. Run 'composer install --dev' to install it.${NC}"
    fi
    
    # ESLint (if configured)
    if [ -f "node_modules/.bin/eslint" ]; then
        echo -e "\n${YELLOW}Running ESLint...${NC}"
        npx eslint resources/js/
    fi
    
    # StyleLint (if configured)
    if [ -f "node_modules/.bin/stylelint" ]; then
        echo -e "\n${YELLOW}Running StyleLint...${NC}"
        npx stylelint "resources/**/*.css"
    fi
}

# Function to fix linting issues
run_fix() {
    echo -e "${GREEN}🔧 Fixing linting issues...${NC}"
    
    # PHP_CodeSniffer
    if [ -f "vendor/bin/phpcbf" ]; then
        echo -e "\n${YELLOW}Fixing PHP code style...${NC}"
        ./vendor/bin/phpcbf --standard=PSR12 app/ tests/
    fi
    
    # ESLint (if configured)
    if [ -f "node_modules/.bin/eslint" ]; then
        echo -e "\n${YELLOW}Fixing JavaScript code style...${NC}"
        npx eslint --fix resources/js/
    fi
    
    # StyleLint (if configured)
    if [ -f "node_modules/.bin/stylelint" ]; then
        echo -e "\n${YELLOW}Fixing CSS code style...${NC}"
        npx stylelint --fix "resources/**/*.css"
    fi
}

# Function to clean up
run_clean() {
    echo -e "${GREEN}🧹 Cleaning up...${NC}"
    
    # Remove node_modules
    if [ -d "node_modules" ]; then
        echo -e "${YELLOW}Removing node_modules...${NC}"
        rm -rf node_modules/
    fi
    
    # Remove vendor
    if [ -d "vendor" ]; then
        echo -e "${YELLOW}Removing vendor directory...${NC}"
        rm -rf vendor/
    fi
    
    # Remove compiled assets
    if [ -d "public/css" ]; then
        echo -e "${YELLOW}Removing compiled CSS...${NC}"
        rm -rf public/css/
    fi
    
    if [ -d "public/js" ]; then
        echo -e "${YELLOW}Removing compiled JS...${NC}"
        rm -rf public/js/
    fi
    
    echo -e "${GREEN}✓ Cleanup complete${NC}"
}

# Main script execution
case "$1" in
    setup)
        run_setup
        ;;
    dev)
        run_dev
        ;;
    build)
        run_build
        ;;
    watch)
        run_watch
        ;;
    test)
        run_tests
        ;;
    lint)
        run_lint
        ;;
    fix)
        run_fix
        ;;
    clean)
        run_clean
        ;;
    help|--help|-h|*)
        show_help
        ;;
esac

exit 0
