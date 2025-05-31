# Eheca Development Setup Guide

This guide outlines the recommended development environment setup for Eheca, optimized for both performance and production parity.

## 🚀 Recommended Setup: Hybrid Development Environment

### 🌟 Key Features
- **Native PHP/Node.js** for maximum development speed
- **Containerized Services** for consistency
- **Production-like** environment
- **Minimal overhead**
- **Easy debugging**

## 🖥️ System Requirements

### Host Machine
- **OS**: Linux (Ubuntu/Debian recommended)
- **PHP**: 8.4+ with standard extensions
- **Node.js**: 18+ LTS
- **Composer**: Latest version
- **Docker** + Docker Compose
- **Git**

### Recommended Tools
- **IDE**: VS Code or PHPStorm
- **Database Client**: TablePlus, DBeaver, or MySQL Workbench
- **API Client**: Postman or Insomnia
- **Terminal**: iTerm2 (macOS) or GNOME Terminal (Linux)

## 🛠️ Installation

### 1. Install System Dependencies

#### Ubuntu/Debian
```bash
# Update package list
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.4 php8.4-{bcmath,ctype,curl,dom,fileinfo,gd,mbstring,pdo_mysql,redis,zip,xml,opcache,intl,simplexml}

# Install Node.js (using nvm recommended)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
source ~/.bashrc  # or restart terminal
nvm install --lts
nvm use --lts

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installations
docker --version
docker-compose --version
php -v
node -v
npm -v
composer --version
```

### 2. Clone the Repository

```bash
git clone https://github.com/your-org/eheca.git
cd eheca
```

### 3. Configure Environment

Copy the example environment file and update with your local settings:

```bash
cp .env.example .env
nano .env  # or use your preferred editor
```

### 4. Start Docker Services

```bash
docker-compose up -d
```

### 5. Install PHP Dependencies

```bash
composer install
```

### 6. Install Frontend Dependencies

```bash
cd frontend
npm install
npm run dev
```

### 7. Initialize Database

```bash
# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Load fixtures (if available)
php bin/console doctrine:fixtures:load --no-interaction
```

### 8. Start Development Server

```bash
# Backend (Symfony)
symfony server:start -d

# Frontend (Vite)
cd frontend
npm run dev
```

## 🔧 Development Tools

### Code Quality

```bash
# PHP Code Sniffer
composer run cs-check
composer run cs-fix

# PHPStan (static analysis)
composer run phpstan

# PHPUnit Tests
composer test
```

### Common Tasks

```bash
# Clear cache
php bin/console cache:clear

# Watch for changes (frontend)
npm run watch

# Run tests with coverage
composer test-coverage
```

## 🛠️ IDE Configuration

### VS Code Extensions
- PHP Intelephense
- PHP Debug
- PHP Namespace Resolver
- Twig
- Symfony
- ESLint
- Prettier
- Tailwind CSS IntelliSense

### PHPStorm Plugins
- Symfony Support
- PHP Annotations
- PHP Toolbox
- PHP Inspections (EA Extended)
- Twig
- NodeJS
- Database Tools

## 🔄 Common Issues

### Permissions Issues
```bash
# Fix file permissions
sudo chown -R $USER:$USER .
chmod -R 755 storage bootstrap/cache
```

### Docker Issues
```bash
# Rebuild containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## 📚 Next Steps

1. Read the [Module Development Guide](./module-development.md)
2. Check out the [API Documentation](../api/README.md)
3. Review [Coding Standards](../architecture/decisions.md#coding-standards)
sudo apt install -y docker.io docker-compose
sudo usermod -aG docker $USER
# Log out and back in for group changes to take effect
```

### 2. Clone the Repository
```bash
git clone https://github.com/your-org/eheca.git
cd eheca
```

### 3. Configure Environment
```bash
# Copy example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Install PHP dependencies
composer install

# Install frontend dependencies
cd frontend
npm install
```

### 4. Start Containerized Services
```bash
# Start all services in detached mode
docker-compose up -d
```

### 5. Database Setup
```bash
# Run migrations and seeders
php artisan migrate --seed
```

## 🚦 Development Workflow

### Running the Application
- **Backend API**: `http://localhost:8000`
- **Frontend**: `http://localhost:3000` (Vite dev server)
- **MailHog**: `http://localhost:8025`
- **Database**: `localhost:3306` (MySQL/MariaDB)
- **Redis**: `localhost:6379`

### Common Commands

#### PHP Artisan
```bash
php artisan [command]
```

#### Composer
```bash
composer [command]
```

#### Frontend (Vite)
```bash
# Development server with HMR
npm run dev

# Production build
npm run build
```

## 🐛 Debugging

### Xdebug Configuration
Xdebug is pre-configured for native PHP. Use these IDE settings:

- **Host**: `localhost`
- **Port**: `9003`
- **IDE Key**: `VSCODE` (or your IDE's key)

### Browser Extensions
- **Xdebug Helper** for Chrome/Firefox
- **Live Server** for VS Code

## 🔍 Troubleshooting

### Common Issues

#### Port Conflicts
```bash
# Check running services
sudo lsof -i :8000

# Kill process if needed
kill -9 [PID]
```

#### Docker Issues
```bash
# Rebuild containers
docker-compose build --no-cache

# View logs
docker-compose logs -f [service]
```

#### Database Connection
1. Verify `.env` matches `docker-compose.yml`
2. Check if MySQL is running: `docker-compose ps`
3. Test connection: `mysql -h 127.0.0.1 -u root -p`

## 🚀 Production Deployment

For production deployment, see our [Production Deployment Guide](../production/deployment.md).

## 📚 Next Steps
- [API Documentation](./api/README.md)
- [Module Development Guide](./module-development.md)
- [Testing Guide](./testing.md)

## 🤝 Contributing

1. Create a new branch: `git checkout -b feature/your-feature`
2. Make your changes
3. Run tests: `php artisan test`
4. Submit a pull request

## 📄 License

This project is [MIT licensed](../LICENSE).
