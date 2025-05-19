<?php

declare(strict_types=1);

use Eheca\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

// Load environment variables if .env file exists
if (file_exists(dirname(__DIR__).'/.env')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// Set the environment and debug mode
$env = $_SERVER['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

if ($debug) {
    umask(0000);
    Debug::enable();
}

// Initialize the kernel
$kernel = new Kernel($env, $debug);
$request = Request::createFromGlobals();

// Handle the request and send the response
$response = $kernel->handle($request);
$response->send();

// Terminate the request
$kernel->terminate($request, $response);
