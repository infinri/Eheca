# Eheca Framework 🚀

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-8.4%2B-blue.svg)](https://www.php.net/)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-6c71c4.svg)](https://www.php-fig.org/psr/psr-12/)
[![Build Status](https://github.com/infinri/Eheca/actions/workflows/php.yml/badge.svg)](https://github.com/infinri/Eheca/actions)
[![Code Coverage](https://codecov.io/gh/infinri/Eheca/branch/main/graph/badge.svg)](https://codecov.io/gh/infinri/Eheca)

A modern, high-performance PHP framework built with Symfony components, following PSR standards and best practices.

## 🚀 Production Deployment

### Server Requirements

- PHP 8.4 or higher with these extensions:
  - PDO (for database)
  - OpenSSL (for security)
  - cURL (for HTTP requests)
  - JSON (for API responses)
  - Intl (for internationalization)
- Web server (Nginx/Apache)
- MySQL/MariaDB/PostgreSQL (if using database)
- Composer 2.5+

### Quick Deployment

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/Eheca.git
   cd Eheca
   ```

2. **Set up environment**
   ```bash
   cp .env.prod .env
   # Edit .env with your production settings
   ```

3. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

4. **Set up web server**
   - See `nginx.conf.example` for Nginx configuration
   - Point document root to `/path/to/Eheca/public`
   - Set up HTTPS with Let's Encrypt

5. **Set permissions**
   ```bash
   chmod -R 755 var/
   chown -R www-data:www-data var/
   ```

6. **Clear cache**
   ```bash
   APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
   ```

7. **Set up cron jobs**
   ```
   # Run every minute
   * * * * * cd /path/to/Eheca && php bin/console messenger:consume async -vv
   ```

### Using Docker (Alternative)

1. Install Docker and Docker Compose
2. Run: `docker-compose up -d`
3. Access the site at `http://localhost:8080`

## ✨ Features

- **Modern PHP** - Built with PHP 8.4+ features and strict typing
- **PSR Standards** - Follows PSR-4, PSR-11, PSR-15, and PSR-17 standards
- **Dependency Injection** - Symfony DI container for managing class dependencies
- **Template Engine** - Twig integration for beautiful templates
- **Database** - Doctrine DBAL for database abstraction
- **Logging** - Monolog integration for comprehensive logging
- **Security** - JWT authentication support
- **Frontend** - Asset management with Symfony Asset component
- **Testing** - PHPUnit and PHPStan for testing and static analysis

## 🚀 Quick Start

### Requirements

- PHP 8.4 or higher
- Composer 2.5+
- Web server (Apache/Nginx) or PHP built-in server
- MySQL 8.0+/MariaDB 10.5+/PostgreSQL 13+ (optional, for database features)
- Node.js 18+ (for frontend asset compilation)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/infinri/Eheca.git
   cd Eheca
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Copy the example environment file and update it with your configuration:
   ```bash
   cp .env.example .env
   ```

4. Generate an application secret key:
   ```bash
   php -r "echo bin2hex(random_bytes(32));"
   ```
   Add the generated key to your `.env` file as `APP_SECRET`.

5. Set up your web server to point to the `public/` directory.

   **For development, you can use the built-in PHP server:**
   ```bash
   php -S localhost:8000 -t public
   ```

6. Open your browser and visit: http://localhost:8000

## 📁 Project Structure

```
eheca/
├── config/              # Configuration files
├── public/              # Web server root
│   ├── css/             # Compiled CSS
│   ├── js/              # JavaScript files
│   └── index.php        # Front controller
├── resources/           # Application resources
│   ├── assets/          # Frontend assets (SCSS, JS)
│   └── views/           # Twig templates
├── src/                 # Application source code
│   ├── Controller/      # Controllers
│   ├── Entity/          # Doctrine entities
│   └── Kernel.php       # Application kernel
├── tests/               # Test files
├── var/                 # Cache and logs
└── vendor/              # Composer dependencies
```

## 🛠️ Development

### Running Tests

```bash
# Install dependencies
composer install

# Run PHPUnit tests
composer test

# Run tests with coverage report
composer test:coverage

# Run PHPStan for static analysis
composer stan

# Run PHP CS Fixer
composer cs:fix

# Check coding standards
composer cs:check
```

### Coding Standards

This project follows PSR-12 coding standards. You can check and fix the code style with:

```bash
composer cs:check   # Check coding standards
composer cs:fix     # Fix coding standards
```

## 🚀 Performance

Eheca Framework is optimized for performance with:
- OPcache enabled by default
- Class autoloading optimization
- Environment-based configuration loading
- Built-in HTTP caching
- Database query optimization

## 🔒 Security

Security features include:
- CSRF protection
- XSS prevention
- Secure session handling
- Environment-based configuration
- Security advisories monitoring

## 📝 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

## 📈 Versioning

We use [Semantic Versioning](https://semver.org/). For the versions available, see the [tags on this repository](https://github.com/infinri/Eheca/tags).

## 🤝 Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## 📚 Documentation

For full documentation, please visit our [Wiki](https://github.com/infinri/Eheca/wiki).

## 🤝 Contributing

We welcome contributions! Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## 🔗 Community

- [GitHub Issues](https://github.com/infinri/Eheca/issues) - For bug reports and feature requests
- [Discussions](https://github.com/infinri/Eheca/discussions) - For questions and discussions
- [Wiki](https://github.com/infinri/Eheca/wiki) - For documentation and guides

## 📧 Contact

For any questions or support, please open an issue on GitHub or contact the maintainers.

4. Configure your environment:
   ```bash
   cp .env.example .env
   php bin/console app:generate-key
   ```

5. Update the `.env` file with your database credentials and other settings.

6. Run database migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

7. Build frontend assets:
   ```bash
   npm run dev
   # or for production:
   # npm run build
   ```

8. Start the development server:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

Visit `http://localhost:8000` in your browser to see the welcome page.

## 🏗️ Project Structure

```
Eheca/
├── app/                    # Application code
│   ├── code/               # Modules (Vendor_Module)
│   ├── design/             # Themes
│   └── etc/                # Global configuration
├── bin/                    # Console commands
├── config/                 # Environment configurations
├── docs/                   # Documentation
├── public/                 # Web root
├── resources/              # Frontend assets
├── src/                    # Core framework
├── tests/                  # Test suites
├── var/                    # Runtime files
└── vendor/                 # Composer dependencies
```

## 🧪 Testing

Run PHPUnit tests:
```bash
composer test
```

Run static analysis:
```bash
composer analyse
```

Run frontend tests:
```bash
npm test
```

## 🛠 Development

### Code Style

Check code style:
```bash
composer cs-check
```

Fix code style:
```bash
composer cs-fix
```

### Database

Create migration:
```bash
php bin/console doctrine:migrations:diff
```

Run migrations:
```bash
php bin/console doctrine:migrations:migrate
```

## 🤝 Contributing

We welcome contributions! Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🔗 Connect

- [GitHub](https://github.com/infinri)
- [Documentation](https://eheca.dev/docs) (Coming Soon)

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/infinri">Infinri</a>
</p>
