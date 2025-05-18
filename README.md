# Eheca Framework 🚀

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-8.2%2B-blue.svg)](https://www.php.net/)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-6c71c4.svg)](https://www.php-fig.org/psr/psr-12/)
[![Static Analysis](https://github.com/infinri/Eheca/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/infinri/Eheca/actions)
[![Tests](https://github.com/infinri/Eheca/actions/workflows/tests.yml/badge.svg)](https://github.com/infinri/Eheca/actions)

A high-performance, modular PHP framework designed for building enterprise-grade web applications with a focus on developer experience and modern PHP practices.

## ✨ Features

- **Modular Architecture** - Build applications with independent, reusable modules
- **High Performance** - Optimized with OPcache, JIT compilation, and efficient resource management
- **Modern PHP** - Built with PHP 8.2+ features and strict typing
- **PSR Standards** - Follows PSR-4, PSR-7, PSR-11, PSR-12, and PSR-15 standards
- **Dependency Injection** - Symfony DI container for managing class dependencies
- **Template Engine** - Twig integration with BEM methodology
- **API-First** - Built-in OpenAPI/Swagger documentation
- **Frontend Tooling** - Webpack, SCSS, and RequireJS integration
- **Security** - JWT authentication, CSRF protection, and input validation
- **Testing** - PHPUnit, Pest, and Cypress for comprehensive test coverage

## 🚀 Quick Start

### Requirements

- PHP 8.2 or higher
- Composer 2.0+
- Node.js 18+ (for frontend assets)
- MySQL 8.0+ / MariaDB 10.11+ / PostgreSQL 13+
- Redis 6.0+ (recommended for caching and sessions)

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

3. Install frontend dependencies:
   ```bash
   npm install
   ```

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
