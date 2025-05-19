<?php

declare(strict_types=1);

namespace Eheca\Controller;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Base controller that provides common functionality to all controllers
 */
abstract class AbstractController
{
    protected ?ContainerInterface $container;
    protected ?LoggerInterface $logger;
    protected ?Environment $twig;

    public function __construct(
        ?ContainerInterface $container = null,
        ?LoggerInterface $logger = null,
        ?Environment $twig = null
    ) {
        $this->container = $container;
        $this->logger = $logger;
        $this->twig = $twig;
    }

    /**
     * Renders a template and returns a Response object
     *
     * @param string $view The template path
     * @param array $parameters Template variables
     * @param Response|null $response The response object to use
     * @return Response
     */
    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        if (null === $this->twig) {
            throw new \LogicException('The Twig Bundle is not available. Try running "composer require symfony/twig-bundle".');
        }

        $content = $this->twig->render($view, $parameters);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * Returns a JSON response
     *
     * @param mixed $data The response data
     * @param int $status The response status code
     * @param array $headers Additional headers
     * @return JsonResponse
     */
    protected function json($data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Returns a 404 Not Found response
     * 
     * @param string $message The error message
     * @return Response
     */
    protected function createNotFoundResponse(string $message = 'Not Found'): Response
    {
        return new Response($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Gets a service by its ID
     * 
     * @param string $id The service ID
     * @return object The service
     */
    protected function get(string $id): object
    {
        if (null === $this->container) {
            throw new \LogicException('The container is not available.');
        }

        return $this->container->get($id);
    }

    /**
     * Gets a parameter by its name
     * 
     * @param string $name The parameter name
     * @return mixed
     */
    protected function getParameter(string $name)
    {
        if (null === $this->container) {
            throw new \LogicException('The container is not available.');
        }


        return $this->container->getParameter($name);
    }

    /**
     * Checks if a parameter exists
     * 
     * @param string $name The parameter name
     * @return bool
     */
    protected function hasParameter(string $name): bool
    {
        if (null === $this->container) {
            throw new \LogicException('The container is not available.');
        }

        return $this->container->hasParameter($name);
    }

    /**
     * Logs a message
     * 
     * @param string $message The log message
     * @param array $context The log context
     * @return void
     */
    protected function log(string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->info($message, $context);
        }
    }
}
