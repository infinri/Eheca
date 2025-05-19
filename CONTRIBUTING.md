# Contributing to Eheca Framework

Thank you for considering contributing to Eheca Framework! We appreciate your time and effort to help improve this project.

## Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs
- Ensure the bug was not already reported by searching in [GitHub Issues](https://github.com/infinri/Eheca/issues).
- If you're unable to find an open issue addressing the problem, [open a new one](https://github.com/infinri/Eheca/issues/new).
- Be sure to include a title and clear description, as much relevant information as possible, and a code sample or an executable test case demonstrating the expected behavior.

### Suggesting Enhancements
- Open a new issue with a clear title and description explaining the enhancement.
- Include as many details as possible with steps, examples, and any related issues.

### Your First Code Contribution
1. Fork the repository.
2. Create a new branch for your feature or bugfix: `git checkout -b feature/amazing-feature`.
3. Make your changes following the coding standards.
4. Add tests for your changes.
5. Run the test suite and ensure all tests pass.
6. Commit your changes: `git commit -m 'Add some amazing feature'`.
7. Push to the branch: `git push origin feature/amazing-feature`.
8. Open a Pull Request.

## Development Setup

### Prerequisites
- PHP 8.4+
- Composer 2.5+
- Node.js 18+ (for frontend development)
- MySQL 8.0+/MariaDB 10.5+/PostgreSQL 13+ (for database features)

### Installation
1. Fork and clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your environment
4. Generate application key: `php bin/console app:generate-key`
5. Run database migrations: `php bin/console doctrine:migrations:migrate`

### Running Tests
```bash
# Run all tests
composer test

# Run tests with coverage
composer test:coverage

# Run static analysis
composer stan
```

### Coding Standards
This project follows PSR-12 coding standards.

Check code style:
```bash
composer cs:check
```

Fix code style:
```bash
composer cs:fix
```

## Pull Request Process
1. Ensure any install or build dependencies are removed before the end of the layer when doing a build.
2. Update the README.md with details of changes to the interface, including new environment variables, exposed ports, useful file locations, and container parameters.
3. Increase the version numbers in any examples files and the README.md to the new version that this Pull Request would represent. The versioning scheme we use is [SemVer](http://semver.org/).
4. Your pull request will be reviewed by the maintainers and may require changes before being approved.

## License
By contributing, you agree that your contributions will be licensed under the [MIT License](LICENSE).
