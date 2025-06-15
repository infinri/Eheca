<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;

/**
 * Simple per-IP rate-limiting middleware backed by APCu.
 *
 * Intended for lightweight environments where adding Redis is overkill.
 * Counts requests in a fixed window. Ideal for low-traffic endpoints such as a
 * contact form.
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    private int $maxAttempts;
    private int $decaySeconds;

    public function __construct(int $maxAttempts = 5, int $decaySeconds = 60)
    {
        $this->maxAttempts  = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        // Fallback to REMOTE_ADDR when behind simple proxies. Users should place
        // a trusted proxy middleware in front if needed.
        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';

        // APCu is per-PHP-FPM worker. Good enough for a single node or sticky sessions.
        if (function_exists('apcu_fetch')) {
            $key = 'rate:' . $ip;
            $attempts = apcu_fetch($key, $success);
            if (!$success) {
                $attempts = 0;
            }
            $attempts++;
            apcu_store($key, $attempts, $this->decaySeconds);

            if ($attempts > $this->maxAttempts) {
                return $this->tooManyRequestsResponse();
            }
        }
 
        return $handler->handle($request); 
    }

    private function tooManyRequestsResponse(): Response
    {
        $response = new SlimResponse(429);
        $payload  = [
            'success' => false,
            'message' => 'Too many requests – please slow down.',
        ];
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
