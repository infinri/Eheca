<?php

use DI\Container;
use League\Plates\Engine;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use App\Services\NotificationService;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Support\LoggerFactory;
use PDO;

require __DIR__ . '/../vendor/autoload.php';

// Start PHP session for CSRF protection (required by Slim\Csrf)
session_start();

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set timezone
date_default_timezone_set('UTC');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../storage/logs/php_errors.log');

// Create logs directory if it doesn't exist
if (!is_dir(__DIR__ . '/../storage/logs')) {
    mkdir(__DIR__ . '/../storage/logs', 0755, true);
}

// ---------------------------------------------------------------------
// Database bootstrap (SQLite via PDO)
// ---------------------------------------------------------------------
// Resolve DB path from environment or default to storage/database.sqlite
$dbPath = $_ENV['DB_PATH'] ?? (__DIR__ . '/../storage/database/database.sqlite');

// Ensure directory exists
$dbDir = dirname($dbPath);
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    // Recommended PDO settings
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create table ONCE – ignored if exists
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS contact_submissions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            company TEXT,
            project_type TEXT NOT NULL,
            contact_method TEXT,
            source TEXT,
            message TEXT NOT NULL,
            created_at TEXT NOT NULL
        )'
    );
} catch (Throwable $e) {
    // Log fatal error and rethrow
    LoggerFactory::get()->error('Database initialization failed: ' . $e->getMessage());
    throw $e;
}

// Create Container
$container = new Container();
AppFactory::setContainer($container);

// Create App
$app = AppFactory::create();

// Add Routing Middleware (must come before BodyParsing, CSRF, etc.)
$app->addRoutingMiddleware();

// Add body parsing middleware BEFORE CSRF so it parses the request first
$app->addBodyParsingMiddleware();

// Register CSRF middleware (adds CSRF attributes to every request)
$csrf = new Guard($app->getResponseFactory());
$app->add($csrf);

// Add custom error handler
$customErrorHandler = function (
    \Slim\Http\ServerRequest $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {
    $statusCode = 500;
    $errorMessage = 'Internal Server Error';
    
    if ($exception instanceof HttpNotFoundException) {
        $statusCode = 404;
        $errorMessage = 'Not Found';
    } elseif ($exception instanceof HttpMethodNotAllowedException) {
        $statusCode = 405;
        $errorMessage = 'Method Not Allowed';
    }
    
    $response = $app->getResponseFactory()->createResponse($statusCode);
    
    // Log the error
    LoggerFactory::get()->error(sprintf(
        "[%s] %s in %s:%d\nStack trace:\n%s",
        date('Y-m-d H:i:s'),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    ));
    
    if ($displayErrorDetails) {
        $data = [
            'status' => 'error',
            'message' => $errorMessage,
            'error' => [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $displayErrorDetails ? $exception->getTrace() : []
            ]
        ];
        
        $response->getBody()->write(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    } else {
        $response->getBody()->write(
            json_encode(['status' => 'error', 'message' => $errorMessage])
        );
    }
    
    return $response->withHeader('Content-Type', 'application/json');
};

// Add Error Middleware with custom handler
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

// Register components
// Expose Plates Engine by its class for autowiring
$container->set(Engine::class, function($c) {
    return $c->get('view');
});
$container->set('view', function() {
    $viewsPath = __DIR__ . '/../resources/views';
    
    // Debug: Check if views directory exists and is readable
    if (!is_dir($viewsPath) || !is_readable($viewsPath)) {
        throw new RuntimeException("Views directory not found or not readable: $viewsPath");
    }
    
    // Create new Plates engine with the views directory as the base
    $plates = new Engine($viewsPath);
    
    // Set the file extension
    $plates->setFileExtension('php');
    
    // Add template folders
    $plates->addFolder('layouts', $viewsPath . '/layouts');
    $pagesPath = $viewsPath . '/pages';
    if (is_dir($pagesPath)) {
        $plates->addFolder('pages', $pagesPath);
    }
    
    return $plates;
});

// Register HomeController with dependency injection
$container->set(\App\Http\Controllers\HomeController::class, function($c) {
    return new \App\Http\Controllers\HomeController($c->get('view'));
});

// Register NotificationService
$container->set(NotificationService::class, function() {
    return new NotificationService();
});

// Register PDO (shared instance)
$container->set(PDO::class, function() use ($pdo) {
    return $pdo;
});

// Register PageController with dependency injection
$container->set(PageController::class, function($c) {
    return new PageController(
        $c->get('view')
    );
});

// Register ContactController with dependency injection
$container->set(ContactController::class, function($c) {
    return new ContactController(
        $c->get('view'),
        $c->get(NotificationService::class),
        $c->get(PDO::class)
    );
});

// Add Plates middleware
$app->add(function ($request, $handler) use ($container) {
    $container->get('view')->addData([
        'uri' => $request->getUri(),
        'baseUrl' => (string)$request->getUri()->withPath('')->withQuery('')->withFragment('')
    ]);
    return $handler->handle($request);
});

// Register routes
require __DIR__ . '/../routes/web.php';

$app->run();
