<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use League\Plates\Engine;

/**
 * HomeController: serves landing page only.
 */
class HomeController
{
    private Engine $view;

    public function __construct(Engine $view)
    {
        $this->view = $view;
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $data = [
            'title'   => 'Welcome to Eheca',
            'message' => "I handle the micro-crap so you don't have to.",
            'active'  => 'home',
        ];

        try {
            $content = $this->view->render('home', $data);
            $response->getBody()->write($content);
            return $response->withHeader('Content-Type', 'text/html');
        } catch (\Throwable $e) {
            $error = sprintf(
                "Error rendering template: %s in %s:%d",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
            error_log($error);
            $response->getBody()->write('<pre>' . htmlspecialchars($error) . '</pre>');
            return $response->withStatus(500);
        }
    }
}