<?php

namespace Modules\Core_Auth\Controller;

use Modules\Core_Auth\Entity\Customer;
use Modules\Core_Auth\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthController extends AbstractController
{
    /**
     * Register a new user
     * 
     * @Route("/api/register", name="auth_register", methods={"POST"})
     */
    public function register(
        Request $request, 
        CustomerRepository $customerRepo,
        \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher,
        \Psr\Log\LoggerInterface $logger
    ): JsonResponse {
        $logger->info('Registration request received', ['ip' => $request->getClientIp()]);
        
        try {
            // Get and validate request data
            $rawContent = $request->getContent();
            $data = json_decode($rawContent, true);
            
            $logger->debug('Raw request content', ['content' => $rawContent]);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $logger->error('Invalid JSON payload', [
                    'error' => json_last_error_msg(),
                    'content' => $rawContent
                ]);
                return $this->json(
                    ['error' => 'Invalid JSON payload: ' . json_last_error_msg()], 
                    Response::HTTP_BAD_REQUEST
                );
            }
            
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
            $firstName = $data['firstName'] ?? null;
            $lastName = $data['lastName'] ?? null;

            // Basic validation
            if (!$email) {
                $logger->warning('Email is required');
                return $this->json(
                    ['error' => 'Email is required.'], 
                    Response::HTTP_BAD_REQUEST
                );
            }
            
            if (!$password) {
                $logger->warning('Password is required', ['email' => $email]);
                return $this->json(
                    ['error' => 'Password is required.'], 
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $logger->warning('Invalid email format', ['email' => $email]);
                return $this->json(
                    ['error' => 'Invalid email format.'], 
                    Response::HTTP_BAD_REQUEST
                );
            }
            
            // Validate password strength
            if (strlen($password) < 8) {
                $logger->warning('Password too short', ['email' => $email]);
                return $this->json(
                    ['error' => 'Password must be at least 8 characters long.'], 
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Check if user already exists
            $logger->debug('Checking if user exists', ['email' => $email]);
            $existingUser = $customerRepo->findByEmail($email);
            if ($existingUser) {
                $logger->warning('Registration attempt with existing email', ['email' => $email]);
                return $this->json(
                    ['error' => 'Email already registered.'], 
                    Response::HTTP_CONFLICT
                );
            }

            // Create and save user using repository
            $logger->info('Creating new user', ['email' => $email]);
            
            try {
                $user = $customerRepo->createCustomer(
                    $email,
                    $password,
                    $passwordHasher,
                    $firstName,
                    $lastName
                );
                
                $logger->info('User created successfully', [
                    'userId' => $user->getId(),
                    'email' => $user->getEmail()
                ]);

                return $this->json(
                    [
                        'message' => 'User registered successfully',
                        'userId' => $user->getId(),
                        'email' => $user->getEmail()
                    ],
                    Response::HTTP_CREATED
                );
            } catch (\Exception $e) {
                $logger->error('Error creating user', [
                    'email' => $email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            // Log the error with more context
            $errorId = uniqid('reg_', true);
            $logger->critical('Registration error', [
                'error_id' => $errorId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $data ?? []
            ]);
            
            return $this->json(
                [
                    'error' => 'An error occurred during registration. Please try again.',
                    'error_id' => $errorId
                ], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @Route("/api/account", name="auth_account", methods={"GET"})
     */
    public function account(#[CurrentUser] $user = null): JsonResponse
    {
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}
