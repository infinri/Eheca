<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/vendor/autoload.php';

(new Dotenv())->bootEnv(__DIR__.'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$container = $kernel->getContainer();
$connection = $container->get('doctrine')->getConnection();

try {
    $tables = $connection->executeQuery('SHOW TABLES')->fetchAllAssociative();
    echo "Database connection successful.\n";
    echo "Tables in database:\n";
    print_r($tables);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Database URL: " . $_ENV['DATABASE_URL'] . "\n";
