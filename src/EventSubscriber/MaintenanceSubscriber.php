<?php

namespace Eheca\EventSubscriber;

use Eheca\Controller\MaintenanceController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $maintenanceController;
    private $twig;
    private $router;
    private $excludedRoutes = [
        'maintenance_status',
        'maintenance_enable',
        'maintenance_disable',
        'health_check',
        'app_login',
        'app_logout',
    ];

    public function __construct(
        MaintenanceController $maintenanceController,
        Environment $twig,
        RouterInterface $router
    ) {
        $this->maintenanceController = $maintenanceController;
        $this->twig = $twig;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // Skip if no route or route is in excluded list
        if (!$route || in_array($route, $this->excludedRoutes, true)) {
            return;
        }

        try {
            $response = $this->maintenanceController->checkMaintenance($request);
            
            if ($response instanceof Response) {
                $this->logMaintenanceAccess($request);
                
                // If it's an API request, return JSON
                if (strpos($request->headers->get('Accept'), 'application/json') !== false) {
                    $event->setResponse($response);
                    return;
                }
                
                // Otherwise, render a nice maintenance page
                $content = $this->twig->render('maintenance.html.twig', [
                    'refresh_after' => 60,
                    'status_code' => Response::HTTP_SERVICE_UNAVAILABLE,
                    'status_text' => 'Maintenance',
                    'message' => $response->getContent(),
                ]);
                
                $response = new Response(
                    $content,
                    Response::HTTP_SERVICE_UNAVAILABLE
                );
                
                $event->setResponse($response);
            }
        } catch (\Exception $e) {
            // Log the error but don't break the application
            error_log('Maintenance mode check failed: ' . $e->getMessage());
        }
    }

    private function logMaintenanceAccess($request): void
    {
        // Log access attempts during maintenance
        error_log(sprintf(
            'Maintenance mode: Blocked access to %s from %s',
            $request->getPathInfo(),
            $request->getClientIp()
        ));
    }
}
