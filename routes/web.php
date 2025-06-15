<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

// Handle favicon requests to avoid polluting error logs
$app->get('/favicon.ico', function (Request $request, Response $response) {
    // Respond with "No Content"; browsers will stop retrying and logs stay clean
    return $response->withStatus(204);
});

// Home page route
$app->get('/', 'App\Http\Controllers\HomeController:index');

// Contact page route
$app->get('/contact', [ContactController::class, 'showForm']);

// Contact form submission route with simple rate limit (5 requests / 60s per IP)
$app->post('/contact', [ContactController::class, 'submitForm'])
    ->add(new \App\Middleware\RateLimitMiddleware(5, 60));

// Static informational pages
$app->get('/about', [PageController::class, 'about']);
$app->get('/examples', [PageController::class, 'examples']);
$app->get('/faq', [PageController::class, 'faq']);
$app->get('/services', [PageController::class, 'services']);

// Example API route
$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/status', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['status' => 'ok']));
        return $response->withHeader('Content-Type', 'application/json');
    });
});
