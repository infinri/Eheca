# Testing Guide

This guide covers how to run and write tests for the Eheca application.

## Table of Contents
- [Running Tests](#running-tests)
- [Test Types](#test-types)
- [Writing Tests](#writing-tests)
- [Test Data](#test-data)
- [Best Practices](#best-practices)

## Running Tests

### Prerequisites
- PHP 8.4+
- Composer
- MySQL/PostgreSQL database for integration tests

### Running All Tests

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test:coverage
```

### Running Specific Tests

```bash
# Run unit tests
./vendor/bin/phpunit --testsuite=unit

# Run integration tests
./vendor/bin/phpunit --testsuite=integration

# Run a specific test file
./vendor/bin/phpunit tests/Unit/ExampleTest.php

# Run a specific test method
./vendor/bin/phpunit --filter=test_example tests/Unit/ExampleTest.php
```

## Test Types

### Unit Tests
- Location: `tests/Unit/`
- Test individual classes and methods in isolation
- Mock all external dependencies
- Fast execution

### Integration Tests
- Location: `tests/Integration/`
- Test interactions between components
- May use real services (database, filesystem)
- Slower than unit tests

### Feature Tests
- Location: `tests/Feature/`
- Test complete features from HTTP request to response
- Simulate user interactions
- Test API endpoints and web routes

## Writing Tests

### Test Structure

```php
<?php

test('it validates user input', function () {
    // Arrange
    $data = [
        'name' => 'Test User',
        'email' => 'invalid-email',
    ];

    // Act
    $response = $this->postJson('/api/users', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
```

### Available Assertions

```php
// HTTP assertions
$response->assertStatus(200);
$response->assertJson(['key' => 'value']);
$response->assertJsonValidationErrors(['field']);

// Database assertions
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);
$this->assertDatabaseMissing('users', ['email' => 'nonexistent@example.com']);

// Mocking
$mock = $this->mock(Service::class);
$mock->shouldReceive('method')->once()->andReturn('value');
```

## Test Data

### Factories

```php
// Define a factory
User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
]);

// Create multiple models
User::factory()->count(5)->create();

// States for variations
User::factory()->admin()->create();
```

### Database Transactions

Tests run within database transactions by default, which are rolled back after each test.

```php
// Disable transaction for specific test
use DatabaseTransactions;

public function setUp(): void
{
    parent::setUp();
    $this->withoutExceptionHandling();
}
```

## Best Practices

1. **Naming**
   - Use descriptive test names that explain the expected behavior
   - Follow the pattern: `test_it_[does_something]_when_[condition]`

2. **Structure**
   - Follow Arrange-Act-Assert pattern
   - Keep tests small and focused
   - Test one thing per test

3. **Performance**
   - Mock external services
   - Use database transactions
   - Avoid unnecessary database queries

4. **Maintainability**
   - Use factories for test data
   - Keep test data consistent
   - Add clear assertions

5. **Coverage**
   - Aim for high test coverage (80%+)
   - Focus on business logic, not getters/setters
   - Test edge cases and error conditions

## Continuous Integration

Tests are automatically run on pull requests and merges to the main branch. The build will fail if:
- Any test fails
- Code coverage drops below 80%
- Code style violations are found

## Debugging Tests

To debug a failing test:

```bash
# Run with debug output
./vendor/bin/phpunit --debug

# Stop on first failure
./vendor/bin/phpunit --stop-on-failure

# Filter by test name
./vendor/bin/phpunit --filter=test_user_registration
```

## Code Coverage

To generate a code coverage report:

```bash
# HTML report
composer test:coverage

# Console report
./vendor/bin/phpunit --coverage-text
```

Open `coverage/index.html` in your browser to view the detailed coverage report.
