# Deployment Guide

This guide covers the deployment process for Eheca applications.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Environment Setup](#environment-setup)
- [Deployment Process](#deployment-process)
- [Configuration](#configuration)
- [Scaling](#scaling)
- [Monitoring](#monitoring)
- [Backup & Recovery](#backup--recovery)
- [Troubleshooting](#troubleshooting)

## Prerequisites

### Server Requirements
- Linux (Ubuntu 22.04 LTS recommended)
- Docker 20.10+
- Docker Compose 2.0+
- Minimum 2GB RAM (4GB recommended)
- Minimum 20GB disk space

### Domain & SSL
- Registered domain name
- SSL certificate (Let's Encrypt recommended)
- DNS access for domain configuration

## Environment Setup

### 1. Server Preparation
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y git curl unzip

# Install Docker
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### 2. Clone Repository
```bash
git clone https://github.com/your-org/eheca.git /var/www/eheca
cd /var/www/eheca
```

### 3. Environment Configuration
```bash
cp .env.production .env
nano .env  # Edit configuration
```

## Deployment Process

### 1. First Deployment
```bash
# Build and start containers
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

# Install dependencies
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader

# Generate application key
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate

# Run database migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Cache configuration
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data /var/www/eheca/storage
sudo chown -R www-data:www-data /var/www/eheca/bootstrap/cache
```

### 2. Updating Deployment
```bash
# Pull latest changes
git pull origin main

# Rebuild and restart containers
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

# Run database migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Clear caches
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

## Configuration

### Environment Variables

#### Required
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=eheca
DB_USERNAME=eheca
DB_PASSWORD=your_secure_password

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Optional
```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Scaling

### Horizontal Scaling
1. Set up a load balancer (e.g., Nginx, HAProxy)
2. Configure multiple application instances
3. Use shared storage for sessions and cache

### Database Scaling
1. Set up read replicas
2. Configure database connection in `.env`:
   ```env
   DB_READ_HOST=replica1,replica2
   DB_WRITE_HOST=master
   ```

### Caching
1. Configure Redis for caching:
   ```env
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   ```

## Monitoring

### Logs
```bash
# View application logs
docker-compose -f docker-compose.prod.yml logs -f app

# View Nginx logs
docker-compose -f docker-compose.prod.yml logs -f web
```

### Health Checks
```bash
# Application health check
curl -I http://localhost/health

# Database connection check
docker-compose -f docker-compose.prod.yml exec app php artisan db:show
```

## Backup & Recovery

### Database Backups
```bash
# Create backup
docker exec -t eheca-db-1 pg_dump -U eheca > backup_$(date +%Y-%m-%d).sql

# Restore from backup
cat backup_file.sql | docker exec -i eheca-db-1 psql -U eheca
```

### File Backups
```bash
# Backup storage and .env
sudo tar -czvf eheca_backup_$(date +%Y-%m-%d).tar.gz /var/www/eheca/storage /var/www/eheca/.env

# Restore files
sudo tar -xzvf eheca_backup_2023-01-01.tar.gz -C /
```

## Troubleshooting

### Common Issues

#### 1. Container Fails to Start
```bash
# Check container logs
docker logs <container_id>

# Start in foreground for debugging
docker-compose -f docker-compose.prod.yml up
```

#### 2. Database Connection Issues
```bash
# Test database connection
docker-compose -f docker-compose.prod.yml exec app php artisan db:show

# Check database logs
docker-compose -f docker-compose.prod.yml logs db
```

#### 3. Permission Issues
```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/eheca/storage
sudo chmod -R 775 /var/www/eheca/storage
```

### Upgrading

#### 1. Major Version Upgrades
1. Check upgrade guide in documentation
2. Backup database and files
3. Follow version-specific migration steps
4. Test thoroughly in staging before production

#### 2. Dependency Updates
```bash
# Update PHP dependencies
docker-compose -f docker-compose.prod.yml exec app composer update

# Update NPM dependencies
docker-compose -f docker-compose.prod.yml exec node npm update

# Rebuild assets
docker-compose -f docker-compose.prod.yml exec node npm run prod
```

## Security

### SSL Configuration
```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;
    
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    
    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    
    # HSTS (uncomment after testing)
    # add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    # Rest of your configuration
}
```

### Security Headers
Add to your Nginx configuration:
```nginx
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "no-referrer-when-downgrade" always;
add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
add_header Permissions-Policy "geolocation=(), midi=(), sync-xhr=(), microphone=(), camera=(), magnetometer=(), gyroscope=(), fullscreen=(self), payment=()" always;
```

## Maintenance Mode

### Enable Maintenance Mode
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan down
```

### Disable Maintenance Mode
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan up
```
