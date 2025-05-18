# Eheca Framework 🚀

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-8.2%2B-blue.svg)](https://www.php.net/)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-6c71c4.svg)](https://www.php-fig.org/psr/psr-12/)

A high-performance, modular PHP framework designed for building scalable and maintainable web applications with elegance and efficiency.

## ✨ Features

- **Modular Architecture** - Build applications with independent, reusable modules
- **High Performance** - Optimized for speed with built-in caching and efficient resource management
- **Modern PHP** - Built with PHP 8.2+ features and strict typing
- **PSR Standards** - Follows PSR-4, PSR-11, and PSR-15 standards
- **Dependency Injection** - Powerful DI container for managing class dependencies
- **Template Engine** - Built-in template engine with Twig integration
- **Security** - Built-in protection against common web vulnerabilities
- **RESTful** - Easy API development with RESTful routing
- **Database Agnostic** - Supports multiple database systems through Doctrine DBAL
- **Testing Ready** - Built-in support for PHPUnit and automated testing

## 🚀 Quick Start

### Requirements

- PHP 8.2 or higher
- Composer
- MySQL 5.7+ / PostgreSQL 10+ / SQLite 3.24+
- Web server (Nginx/Apache) with PHP-FPM

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/infinri/Eheca.git
   cd Eheca
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure your environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Update the `.env` file with your database credentials and other settings.

5. Run the development server:
   ```bash
   php serve
   ```

Visit `http://localhost:8000` in your browser to see the welcome page.

## 🏗️ Project Structure

```
Eheca/
├── app/                  # Application core
│   ├── Console/          # Console commands
│   ├── Controllers/      # Application controllers
│   ├── Middleware/       # HTTP middleware
│   ├── Models/           # Database models
│   └── Services/         # Business logic
├── config/               # Configuration files
├── public/               # Publicly accessible files
├── resources/
│   ├── views/           # Template files
│   └── assets/           # Frontend assets
├── routes/               # Route definitions
├── storage/              # Storage for logs, cache, etc.
├── tests/                # Test files
└── vendor/               # Composer dependencies
```

## 🧪 Testing

Run the test suite:

```bash
composer test
```

For code coverage:

```bash
composer test-coverage
```

## 🤝 Contributing

We welcome contributions! Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Thanks to all contributors who have helped shape this project
- Inspired by Laravel, Symfony, and other great PHP frameworks
- Built with ❤️ by [Your Name]

## 🔗 Connect

- [GitHub](https://github.com/infinri)

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/infinri">Infinri</a>
</p>
