<?php

namespace Modules\Core_Auth\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as PasswordHasher;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/api/test', name: 'test_route', methods: ['GET'])]
    public function test(PasswordHasher $passwordHasher): JsonResponse
    {
        return $this->json(['message' => 'Test successful']);
    }
}
