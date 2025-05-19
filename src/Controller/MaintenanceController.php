<?php

namespace Eheca\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

class MaintenanceController extends AbstractController
{
    private $maintenanceFilePath;
    private $logger;
    private $filesystem;
    private $allowedIps;

    public function __construct(
        string $projectDir,
        LoggerInterface $logger,
        ParameterBagInterface $params,
        Filesystem $filesystem = null
    ) {
        $this->maintenanceFilePath = $projectDir . '/var/maintenance.lock';
        $this->logger = $logger;
        $this->filesystem = $filesystem ?: new Filesystem();
        $this->allowedIps = $params->get('maintenance.allowed_ips', []);
    }

    /**
     * @Route("/maintenance/enable", name="maintenance_enable", methods={"POST"})
     */
    public function enableMaintenance(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $content = [
            'enabled_at' => (new \DateTimeImmutable())->format(\DateTime::ATOM),
            'enabled_by' => $this->getUser() ? $this->getUser()->getUserIdentifier() : 'system',
            'allowed_ips' => $this->allowedIps,
            'message' => $request->request->get('message', 'Maintenance mode enabled. Please check back later.')
        ];

        try {
            $this->filesystem->dumpFile(
                $this->maintenanceFilePath,
                json_encode($content, JSON_PRETTY_PRINT)
            );
            
            $this->logger->info('Maintenance mode enabled', [
                'user' => $content['enabled_by'],
                'ip' => $request->getClientIp()
            ]);
            
            return $this->json([
                'status' => 'success',
                'message' => 'Maintenance mode enabled',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Failed to enable maintenance mode: ' . $e->getMessage());
            
            return $this->json([
                'status' => 'error',
                'message' => 'Failed to enable maintenance mode',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/maintenance/disable", name="maintenance_disable", methods={"POST"})
     */
    public function disableMaintenance(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        try {
            if ($this->filesystem->exists($this->maintenanceFilePath)) {
                $this->filesystem->remove($this->maintenanceFilePath);
                
                $this->logger->info('Maintenance mode disabled', [
                    'user' => $this->getUser() ? $this->getUser()->getUserIdentifier() : 'system'
                ]);
                
                return $this->json([
                    'status' => 'success',
                    'message' => 'Maintenance mode disabled'
                ]);
            }
            
            return $this->json([
                'status' => 'info',
                'message' => 'Maintenance mode was not enabled'
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Failed to disable maintenance mode: ' . $e->getMessage());
            
            return $this->json([
                'status' => 'error',
                'message' => 'Failed to disable maintenance mode',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/maintenance/status", name="maintenance_status", methods={"GET"})
     */
    public function maintenanceStatus(): Response
    {
        $isMaintenance = $this->isMaintenanceMode();
        
        if ($isMaintenance) {
            $content = json_decode(file_get_contents($this->maintenanceFilePath), true);
            return $this->json([
                'maintenance' => true,
                'data' => $content
            ]);
        }
        
        return $this->json([
            'maintenance' => false
        ]);
    }

    public function isMaintenanceMode(): bool
    {
        return $this->filesystem->exists($this->maintenanceFilePath);
    }

    public function checkMaintenance(Request $request): ?Response
    {
        if (!$this->isMaintenanceMode()) {
            return null;
        }

        $clientIp = $request->getClientIp();
        
        // Allow access to allowed IPs
        if (in_array($clientIp, $this->allowedIps, true)) {
            return null;
        }

        $content = json_decode(file_get_contents($this->maintenanceFilePath), true);
        
        throw new ServiceUnavailableHttpException(
            3600,
            $content['message'] ?? 'The server is currently in maintenance mode. Please try again later.'
        );
    }
}
