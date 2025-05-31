# Contributing to Eheca

Thank you for your interest in contributing to Eheca! We appreciate your time and effort. This guide will help you get started with contributing to the project.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#-getting-started)
- [Development Workflow](#-development-workflow)
- [Code Style](#-code-style)
- [Commit Guidelines](#-commit-guidelines)
- [Pull Request Process](#-pull-request-process)
- [Reporting Issues](#-reporting-issues)
- [Feature Requests](#-feature-requests)
- [Code Review Process](#-code-review-process)
- [Community](#-community)

## Code of Conduct

By participating in this project, you are expected to uphold our [Code of Conduct](CODE_OF_CONDUCT.md). Please report any unacceptable behavior to [your-email@example.com](mailto:your-email@example.com).

## 🚀 Getting Started

1. **Fork** the repository on GitHub
2. **Clone** your fork locally
   ```bash
   git clone https://github.com/your-username/eheca.git
   cd eheca
   ```
3. **Set up** the development environment (see [Development Setup Guide](docs/guides/development-setup.md))
4. **Create a new branch** for your changes
   ```bash
   git checkout -b feature/your-feature-name
   # or
   git checkout -b fix/issue-number-description
   ```

## 🔄 Development Workflow

1. **Sync** with the main repository
   ```bash
   git remote add upstream https://github.com/your-org/eheca.git
   git fetch upstream
   git merge upstream/main
   ```

2. **Make your changes** following the code style guidelines

3. **Run tests** to ensure nothing is broken
   ```bash
   composer test
   npm test
   ```

4. **Commit your changes** following the commit guidelines

5. **Push** to your fork
   ```bash
   git push origin your-branch-name
   ```

6. **Open a Pull Request** from your fork to the main repository

## 🎨 Code Style

### PHP
- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
- Use type hints and return type declarations where possible
- Add PHPDoc blocks for all classes, methods, and properties
- Keep methods small and focused on a single responsibility

### JavaScript/TypeScript
- Follow [Airbnb JavaScript Style Guide](https://github.com/airbnb/javascript)
- Use ES6+ features where possible
- Add JSDoc comments for complex functions

### Documentation
- Keep documentation up to date with code changes
- Use Markdown for all documentation
- Follow the [Documentation Style Guide](docs/guides/documentation-style.md)

## ✏️ Commit Guidelines

We follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Commit Types

- **feat**: A new feature
- **fix**: A bug fix
- **docs**: Documentation only changes
- **style**: Changes that do not affect the meaning of the code
- **refactor**: A code change that neither fixes a bug nor adds a feature
- **perf**: A code change that improves performance
- **test**: Adding missing tests or correcting existing tests
- **chore**: Changes to the build process or auxiliary tools

### Examples

```
feat(auth): add two-factor authentication
fix(api): prevent race condition in user creation
docs: update installation guide with new requirements
```

## 🔄 Pull Request Process

1. Ensure any install or build dependencies are removed before the end of the layer when doing a build
2. Update the README.md with details of changes to the interface
3. Increase the version numbers in any examples files and the README.md to the new version that this Pull Request would represent
4. You may merge the Pull Request once you have the sign-off of two other developers, or if you do not have permission to do that, you may request the second reviewer to merge it for you

## 🐛 Reporting Issues

When opening an issue, please include:

1. A clear, descriptive title
2. Steps to reproduce the issue
3. Expected vs. actual behavior
4. Screenshots/GIFs if applicable
5. Environment details (OS, browser, PHP/Node versions, etc.)
6. Any relevant logs or error messages

## 💡 Feature Requests

We welcome feature requests! Please include:

1. A clear, descriptive title
2. The problem you're trying to solve
3. Why existing features don't solve your problem
4. Any alternative solutions you've considered

## 👀 Code Review Process

1. A maintainer will review your PR and provide feedback
2. You may be asked to make changes or provide additional information
3. Once approved, a maintainer will merge your PR
4. Your contribution will be included in the next release

## 🌟 Community

Join our community:
- [Discord/Slack Channel](#) (if applicable)
- [Forum](#) (if applicable)
- [Twitter](#) (if applicable)

## 🙏 Thank You!

Your contributions to open source, large or small, make great projects like this possible. Thank you for taking the time to contribute.
