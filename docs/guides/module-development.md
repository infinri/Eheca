# Module Development Guide

This guide provides comprehensive instructions for developing modules in the Eheca application. Eheca follows a modular architecture where each module is a self-contained unit of functionality that can be enabled or disabled without affecting other parts of the system.

## Table of Contents

- [Module Types](#module-types)
- [Module Structure](#module-structure)
- [Creating a New Module](#creating-a-new-module)
- [Module Configuration](#module-configuration)
- [Dependency Injection](#dependency-injection)
- [Routing](#routing)
- [Event System](#event-system)
- [Database Integration](#database-integration)
- [Templating](#templating)
- [Testing](#testing)
- [Best Practices](#best-practices)
- [Module Lifecycle](#module-lifecycle)
- [Versioning](#versioning)
- [Publishing Modules](#publishing-modules)

## Module Types

Eheca supports two main types of modules:

1. **Core Modules** (`Core_*`)
   - Provide fundamental functionality
   - Required for the system to operate
   - Examples: `Core_Auth`, `Core_Routing`

2. **Feature Modules** (`Feature_*`)
   - Add specific features
   - Can be enabled/disabled
   - Examples: `Feature_Blog`, `Feature_Shop`

## Module Structure

```
modules/
  Core_ModuleName/                 # Module directory (PascalCase with underscore)
  │
  ├── src/                         # PHP source code
  │   ├── Controller/               # Controllers (HTTP entry points)
  │   ├── Entity/                  # Doctrine entities
  │   ├── Repository/               # Doctrine repositories
  │   ├── Service/                  # Business logic services
  │   ├── Event/                    # Event classes
  │   ├── EventSubscriber/          # Event subscribers
  │   ├── DependencyInjection/      # Dependency injection configuration
  │   ├── Resources/                # Non-PHP resources
  │   │   └── config/               # Module configuration
  │   └── ModuleName.php            # Module main class
  │
  ├── templates/                  # Twig templates
  │   └── module_name/              # Template namespace
  │
  ├── tests/                      # Tests directory
  │   ├── Unit/                     # Unit tests
  │   ├── Functional/               # Functional tests
  │   └── bootstrap.php             # Test bootstrap file
  │
  ├── public/                     # Public assets (CSS, JS, images)
  │   ├── css/
  │   ├── js/
  │   └── images/
  │
  ├── composer.json               # Module dependencies
  ├── README.md                    # Module documentation
  ├── services.yaml               # Service definitions
  ├── routes.yaml                 # Routing configuration
  ├── events.yaml                 # Event configuration
  └── LICENSE                     # Module license
```

## Creating a New Module

### 1. Generate Module Skeleton

Use the module generator to create a new module:

```bash
php bin/console make:module Core_ModuleName
```

This will create the basic directory structure and necessary files.

### 2. Module Class

Each module must have a main class that extends `AbstractBundle`:

```php
<?php

namespace Core\ModuleName;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ModuleName extends AbstractBundle
{
    public const VERSION = '1.0.0';
    
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
    
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        // Register compiler passes or other build steps
    }
    
    public function boot(): void
    {
        // Module initialization code
    }
    
    public function shutdown(): void
    {
        // Cleanup code
    }
}
```

### 3. Register the Module

Add the module to `config/bundles.php`:

```php
return [
    // Core modules (always loaded)
    Core\ModuleName\ModuleName::class => ['all' => true],
    
    // Feature modules (can be disabled)
    Feature\Blog\BlogModule::class => ['all' => true],
];
```

## Module Configuration

### Configuration Files

1. **services.yaml** - Service definitions:
   ```yaml
   services:
       _defaults:
           autowire: true
           autoconfigure: true
           public: false

       Core\ModuleName\:
           resource: '../src/*'
           exclude:
               - '../src/DependencyInjection/'
               - '../src/Entity/'
               - '../src/Tests/'
   ```

2. **routes.yaml** - Routing configuration:
   ```yaml
   core_module_name:
       resource: '@CoreModuleName/Controller/'
       type: annotation
       prefix: /module-name
   ```

3. **events.yaml** - Event subscribers:
   ```yaml
   Core\ModuleName\EventSubscriber\ExampleSubscriber:
       tags: ['kernel.event_subscriber']
   ```

### Configuration Parameters

Define module-specific parameters in `src/Resources/config/packages/parameters.yaml`:

```yaml
parameters:
    core_module_name.some_parameter: 'default_value'
```

## Dependency Injection

### Services

Define services in `services.yaml`:

```yaml
services:
    Core\ModuleName\Service\ExampleService:
        arguments:
            $someParameter: '%core_module_name.some_parameter%'
            $anotherService: '@some.other.service'
```

### Compiler Passes

For advanced dependency injection, create a compiler pass:

```php
// src/DependencyInjection/Compiler/ExamplePass.php
namespace Core\ModuleName\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExamplePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // Modify container before compilation
    }
}
```

Register it in your module class:

```php
public function build(ContainerBuilder $container): void
{
    parent::build($container);
    $container->addCompilerPass(new ExamplePass());
}
```

## Routing

### Annotations

```php
// src/Controller/ExampleController.php
namespace Core\ModuleName\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController extends AbstractController
{
    /**
     * @Route("/example", name="core_module_name_example")
     */
    public function example()
    {
        return $this->render('@CoreModuleName/example.html.twig');
    }
}
```

### YAML Configuration

```yaml
# routes.yaml
core_module_name_example:
    path: /example
    controller: Core\ModuleName\Controller\ExampleController::example
    methods: [GET]
```

## Event System

### Creating Events

```php
// src/Event/ExampleEvent.php
namespace Core\ModuleName\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ExampleEvent extends Event
{
    public const NAME = 'core_module_name.example';
    
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
}
```

### Dispatching Events

```php
$event = new ExampleEvent($someData);
$this->eventDispatcher->dispatch($event, ExampleEvent::NAME);
```

### Event Subscribers

```php
// src/EventSubscriber/ExampleSubscriber.php
namespace Core\ModuleName\EventSubscriber;

use Core\ModuleName\Event\ExampleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExampleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExampleEvent::NAME => 'onExampleEvent',
        ];
    }
    
    public function onExampleEvent(ExampleEvent $event): void
    {
        // Handle the event
    }
}
```

## Database Integration

### Entities

```php
// src/Entity/Example.php
namespace Core\ModuleName\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="core_module_name_example")
 */
class Example
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    // ... other properties and methods
}
```

### Repositories

```php
// src/Repository/ExampleRepository.php
namespace Core\ModuleName\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Core\ModuleName\Entity\Example;

class ExampleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Example::class);
    }
    
    public function findActive(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.isActive = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();
    }
}
```

### Migrations

Generate a migration:
```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Templating

### Twig Templates

Create templates in `templates/module_name/`:

```twig
{# templates/module_name/example.html.twig #}
{% extends '@CoreLayout/base.html.twig' %}

{% block title %}Example{% endblock %}

{% block content %}
    <div class="module-example">
        <h1>Example Module</h1>
        {{ include('@CoreModuleName/partials/_example.html.twig') }}
    </div>
{% endblock %}
```

### Template Overrides

To override a template from another module:

1. Create a template with the same name in your module
2. Place it in `templates/bundles/OriginalBundle/`

## Testing

### Unit Tests

```php
// tests/Unit/ExampleTest.php
namespace Core\ModuleName\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Core\ModuleName\Service\ExampleService;

class ExampleTest extends TestCase
{
    public function testSomething(): void
    {
        $service = new ExampleService();
        $this->assertTrue($service->someMethod());
    }
}
```

### Functional Tests

```php
// tests/Functional/Controller/ExampleControllerTest.php
namespace Core\ModuleName\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExampleControllerTest extends WebTestCase
{
    public function testExample(): void
    {
        $client = static::createClient();
        $client->request('GET', '/example');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Example Module');
    }
}
```

## Best Practices

### Code Organization

- Follow PSR-4 autoloading standards
- Keep controllers thin - move business logic to services
- Use dependency injection
- Follow SOLID principles
- Write unit tests for business logic
- Write functional tests for controllers

### Performance

- Use services with appropriate scope (shared/private)
- Implement caching where appropriate
- Use DTOs for data transfer
- Optimize database queries
- Use indexes for frequently queried fields

### Security

- Validate all user input
- Use parameterized queries
- Implement proper access controls
- Use CSRF protection for forms
- Sanitize output to prevent XSS

## Module Lifecycle

1. **Installation**
   - Module files are copied to `modules/`
   - Dependencies are installed via Composer
   - Database schema is updated

2. **Activation**
   - Module is registered in the application
   - Services are loaded
   - Routes are registered
   - Event subscribers are registered

3. **Update**
   - New files are copied
   - Database migrations are run
   - Cache is cleared

4. **Deactivation**
   - Module remains installed but is not loaded
   - Routes are disabled
   - Event subscribers are unregistered

5. **Uninstallation**
   - Module data is removed (optional)
   - Database tables are removed (optional)
   - Module files are removed

## Versioning

Follow [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible API changes
- **MINOR** version for backward-compatible functionality
- **PATCH** version for backward-compatible bug fixes

Update the version in:
1. Module class constant
2. `composer.json`
3. `README.md`

## Publishing Modules

### 1. Prepare for Release

- Update `CHANGELOG.md`
- Update version numbers
- Run tests
- Update documentation

### 2. Create a Release

```bash
git tag -a v1.0.0 -m "Initial release"
git push origin v1.0.0
```

### 3. Publish to Packagist

1. Push to a public repository
2. Submit to [Packagist](https://packagist.org/)
3. Enable auto-updates

### 4. Update Documentation

- Update module documentation
- Update main project documentation if needed
- Announce the release
    public function getPath(): string
    {
        return \dirname(__DIR__).'/modules/Core_ModuleName';
    }
}
```

## Module Components

### Controllers
```php
namespace Core\ModuleName\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExampleController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('@Core_ModuleName/example.html.twig');
    }
}
```

### Services
```yaml
# modules/Core_ModuleName/services.yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    Core\ModuleName\:
        resource: '../../modules/Core_ModuleName/src/'
        exclude:
            - '../../modules/Core_ModuleName/src/Entity'
            - '../../modules/Core_ModuleName/src/Kernel.php'
```

### Routing
```yaml
# modules/Core_ModuleName/routes.yaml
module_route:
    path: /module-route
    controller: Core\ModuleName\Controller\ExampleController::index
    methods: [GET]
```

### Events
```yaml
# modules/Core_ModuleName/events.yaml
App\Event\SomeEvent:
    - 'App\EventListener\SomeEventListener::onSomeEvent'
```

## Best Practices

### 1. Keep Modules Focused
Each module should have a single responsibility and be as independent as possible.

### 2. Use Dependency Injection
Always type-hint your dependencies in the constructor.

### 3. Follow Naming Conventions
- Controllers: `PascalCase` with `Controller` suffix
- Services: `snake_case`
- Templates: `snake_case.html.twig`

### 4. Testing
- Write unit tests for services
- Write functional tests for controllers
- Use PHPUnit for backend tests
- Use Jest for frontend tests

## Module Lifecycle

### 1. Installation
```php
// In your Module class
public function boot()
{
    // Module boot logic
}
```

### 2. Update
```php
public function update($currentVersion, $targetVersion)
{
    // Update logic
}
```

### 3. Uninstallation
```php
public function uninstall()
{
    // Cleanup logic
}
```

## Module Dependencies

### 1. Define Dependencies
```json
{
    "require": {
        "core/auth": "^1.0",
        "core/admin": "^1.0"
    }
}
```

### 2. Check Dependencies
```php
if (!$this->container->has('core.auth')) {
    throw new \RuntimeException('The Core_Auth module is required');
}
```

## Frontend Integration

### 1. JavaScript Modules
```javascript
// assets/js/modules/example.js
export default class Example {
    constructor() {
        console.log('Example module loaded');
    }
}
```

### 2. Styles
```scss
// assets/styles/modules/_example.scss
.module-example {
    // Your styles here
}
```

## Testing Your Module

### 1. PHPUnit Configuration
```xml
<testsuites>
    <testsuite name="Module Tests">
        <directory>../../modules/Core_ModuleName/tests</directory>
    </testsuite>
</testsuites>
```

### 2. Running Tests
```bash
php bin/phpunit --testsuite="Module Tests"
```

## Module Distribution

### 1. Versioning
Follow [Semantic Versioning](https://semver.org/).

### 2. Composer Support
Add a `composer.json` to your module:
```json
{
    "name": "eheca/core-modulename",
    "description": "Module description",
    "type": "eheca-module",
    "require": {
        "php": "^8.2",
        "eheca/core": "^1.0"
    },
    "autoload": {
        "psr-4": {
            "Core\\ModuleName\\": "src/"
        }
    }
}
```

## Module Hooks

### 1. Available Hooks
- `module.install` - After module installation
- `module.uninstall` - Before module uninstallation
- `module.enable` - After module is enabled
- `module.disable` - Before module is disabled

### 2. Example Listener
```php
class ModuleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'module.install' => 'onModuleInstall',
        ];
    }

    public function onModuleInstall(ModuleEvent $event)
    {
        if ($event->getModuleName() === 'Core_ModuleName') {
            // Installation logic
        }
    }
}
```
