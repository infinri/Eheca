<?php

namespace Eheca\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Process;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Psr\Cache\CacheItemPoolInterface;

class HealthCheckController extends AbstractController
{
    private $logger;
    private $databaseConnection;
    private $cache;

    public function __construct(
        LoggerInterface $logger,
        Connection $databaseConnection,
        CacheItemPoolInterface $cache
    ) {
        $this->logger = $logger;
        $this->databaseConnection = $databaseConnection;
        $this->cache = $cache;
    }

    /**
     * @Route("/health", name="health_check", methods={"GET"})
     */
    public function healthCheck(Request $request): JsonResponse
    {
        $checks = [
            'status' => 'ok',
            'timestamp' => (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339),
            'components' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'disk_space' => $this->checkDiskSpace(),
                'memory_usage' => $this->checkMemoryUsage(),
            ]
        ];

        // Check if any component is not healthy
        foreach ($checks['components'] as $component) {
            if ($component['status'] !== 'ok') {
                $checks['status'] = 'error';
                break;
            }
        }


        $response = new JsonResponse(
            $checks,
            $checks['status'] === 'ok' ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE
        );

        // Add cache control headers
        $response->setMaxAge(60);
        $response->setPublic();
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    private function checkDatabase(): array
    {
        try {
            $this->databaseConnection->executeQuery('SELECT 1');
            return ['status' => 'ok', 'details' => 'Database connection successful'];
        } catch (\Exception $e) {
            $this->logger->error('Database health check failed: ' . $e->getMessage());
            return [
                'status' => 'error',
                'details' => 'Database connection failed',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkCache(): array
    {
        try {
            $item = $this->cache->getItem('health_check');
            $item->set(time());
            $item->expiresAfter(300); // 5 minutes
            $this->cache->save($item);
            return ['status' => 'ok', 'details' => 'Cache is working'];
        } catch (\Exception $e) {
            $this->logger->error('Cache health check failed: ' . $e->getMessage());
            return [
                'status' => 'error',
                'details' => 'Cache is not available',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkDiskSpace(): array
    {
        try {
            $freeSpace = disk_free_space('/');
            $totalSpace = disk_total_space('/');
            $usedSpace = $totalSpace - $freeSpace;
            $usedPercentage = ($usedSpace / $totalSpace) * 100;

            $status = 'ok';
            $details = 'Disk space is sufficient';

            if ($usedPercentage > 90) {
                $status = 'error';
                $details = 'Disk space is critically low';
            } elseif ($usedPercentage > 80) {
                $status = 'warning';
                $details = 'Disk space is getting low';
            }

            return [
                'status' => $status,
                'details' => $details,
                'data' => [
                    'free' => $this->formatBytes($freeSpace),
                    'total' => $this->formatBytes($totalSpace),
                    'used_percentage' => round($usedPercentage, 2) . '%',
                ]
            ];
        } catch (\Exception $e) {
            $this->logger->error('Disk space check failed: ' . $e->getMessage());
            return [
                'status' => 'error',
                'details' => 'Unable to check disk space',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkMemoryUsage(): array
    {
        try {
            $memoryUsage = memory_get_usage(true);
            $memoryLimit = $this->getMemoryLimit();
            $usagePercentage = ($memoryUsage / $memoryLimit) * 100;

            $status = 'ok';
            $details = 'Memory usage is normal';

            if ($usagePercentage > 90) {
                $status = 'error';
                $details = 'Memory usage is critically high';
            } elseif ($usagePercentage > 80) {
                $status = 'warning';
                $details = 'Memory usage is high';
            }

            return [
                'status' => $status,
                'details' => $details,
                'data' => [
                    'used' => $this->formatBytes($memoryUsage),
                    'limit' => $this->formatBytes($memoryLimit),
                    'usage_percentage' => round($usagePercentage, 2) . '%',
                ]
            ];
        } catch (\Exception $e) {
            $this->logger->error('Memory check failed: ' . $e->getMessage());
            return [
                'status' => 'error',
                'details' => 'Unable to check memory usage',
                'error' => $e->getMessage()
            ];
        }
    }

    private function getMemoryLimit(): int
    {
        $memoryLimit = ini_get('memory_limit');
        
        if ('-1' === $memoryLimit) {
            return PHP_INT_MAX;
        }

        $memoryLimit = strtolower($memoryLimit);
        $max = strtolower(ltrim($memoryLimit, '+'));
        
        if (0 === strpos($max, '0x')) {
            $max = intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = intval($max, 8);
        } else {
            $max = (int) $max;
        }

        switch (substr($memoryLimit, -1)) {
            case 't': $max *= 1024;
            // no break
            case 'g': $max *= 1024;
            // no break
            case 'm': $max *= 1024;
            // no break
            case 'k': $max *= 1024;
        }

        return $max;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
