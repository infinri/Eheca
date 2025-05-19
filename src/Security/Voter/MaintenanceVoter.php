<?php

namespace Eheca\Security\Voter;

use Eheca\Controller\MaintenanceController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;

class MaintenanceVoter extends Voter
{
    public const MANAGE = 'MAINTENANCE_MANAGE';
    public const VIEW = 'MAINTENANCE_VIEW';

    private $security;
    private $maintenanceController;

    public function __construct(Security $security, MaintenanceController $maintenanceController)
    {
        $this->security = $security;
        $this->maintenanceController = $maintenanceController;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::MANAGE, self::VIEW], true);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // If the user is not logged in, deny access
        if (!$user) {
            return false;
        }

        // Check if the user has ROLE_ADMIN for management actions
        if ($attribute === self::MANAGE) {
            return $this->security->isGranted('ROLE_ADMIN');
        }

        // For view access, check if maintenance is enabled
        if ($attribute === self::VIEW) {
            return $this->maintenanceController->isMaintenanceMode();
        }

        return false;
    }
}
