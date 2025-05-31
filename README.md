# Eheca Project

## Project Overview
Eheca is a lightweight, Magento-inspired modular PHP framework designed for rapid delivery of custom websites and micro-apps to small and mid-sized businesses. It strips away monolithic overhead while preserving the extension patterns (modules, events, plugins, DI containers) that seasoned Magento developers rely on.

## Project Structure
```
Eheca/
├── docs/                 # Project documentation
│   ├── requirements/     # Requirements documentation
│   ├── architecture/     # Architecture decisions and diagrams
│   ├── api/              # API documentation
│   └── guides/           # How-to guides and tutorials
├── src/                  # Source code
│   ├── main/             # Main application code
│   └── tests/            # Test files
├── scripts/              # Build and utility scripts
├── .github/              # GitHub workflows and templates
└── docs/
    └── planning/      # Project planning documents
        ├── roadmap.md
        ├── milestones.md
        └── tasks/
```

## Getting Started

### Prerequisites

- PHP 8.4 or higher
- Composer 2.0 or higher
- Node.js 18+ and npm 9+ or Yarn 1.22+
- MySQL 8.0+ or MariaDB 10.5+
- Symfony CLI (optional but recommended)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-organization/eheca.git
   cd eheca
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   cd frontend
   npm install
   ```

4. **Environment Setup**
   - Copy `.env` to `.env.local`
   - Update database credentials and other environment-specific settings
   - Generate application secret: `php bin/console secrets:generate-keys`

### Configuration

1. **Database Setup**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

2. **Frontend Development**
   ```bash
   # From project root
   cd frontend
   npm run dev
   ```

3. **Start Development Server**
   ```bash
   # Using Symfony CLI (recommended)
   symfony server:start -d
   
   # Or using PHP built-in server
   php -S 127.0.0.1:8000 -t public
   ```

### Project Structure

- `/modules` - Core and feature modules
- `/src` - Application source code
- `/config` - Configuration files
- `/public` - Web server root
- `/frontend` - React application
- `/var` - Cache and logs
- `/tests` - Test suites

### Available Commands

- `composer test` - Run PHPUnit tests
- `npm test` - Run frontend tests
- `php bin/console` - List all available Symfony commands

### Development Workflow

1. Create a new branch for your feature/bugfix
2. Make your changes following PSR-12 and Symfony coding standards
3. Write tests for new functionality
4. Run tests and static analysis tools
5. Create a pull request with a clear description of changes
