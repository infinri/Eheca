<?php
namespace Modules\Core_Auth\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Modules\Core_Auth\Repository\CustomerRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use Modules\Core_Auth\Entity\Customer; // If needed for #[CurrentUser] type hint
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * AuthController handles customer registration and account endpoints.
 *
 * Routes:
 *   - POST /register: Register a new customer
 *   - GET /account: Get current user info (must be logged in)
 */
class AuthController extends AbstractController
{
    /**
     * Register a new customer user.
     *
     * @Route("/register", name="auth_register", methods={"POST"})
     */
    public function register(Request $request, CustomerRepository $customerRepo, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            if (!$email || !$password) {
                return $this->json(['error' => 'Email and password are required.'], Response::HTTP_BAD_REQUEST);
            }

            // Check if user already exists
            $existing = $customerRepo->findByEmail($email);
            if ($existing) {
                return $this->json(['error' => 'Email already registered.'], Response::HTTP_CONFLICT);
            }

            // Hash password (using a dummy Customer object for context)
            $customerObj = (object)['email' => $email, 'roles' => ['ROLE_CUSTOMER']];
            $hashedPassword = $passwordHasher->hashPassword($customerObj, $password);
            $customerRepo->insert([
                'email' => $email,
                'password' => $hashedPassword,
                'roles' => json_encode(['ROLE_CUSTOMER'])
            ]);

            return $this->json(['message' => 'Registration successful.'], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            $logMsg = sprintf(
                "[%s] Registration error: %s in %s:%d\nStack trace:\n%s\n",
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            );
            @file_put_contents(__DIR__ . '/../../../var/log/auth_errors.log', $logMsg, FILE_APPEND);
            return $this->json([
                'error' => 'Registration failed. Please contact support.',
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get current customer account info (must be logged in).
     *
     * @Route("/account", name="auth_account", methods={"GET"})
     */
    public function account(#[CurrentUser] $customer): JsonResponse
    {
        try {
            if (!$customer) {
                return $this->json(['error' => 'Not authenticated.'], Response::HTTP_UNAUTHORIZED);
            }
            // Defensive: handle both array and object
            $id = $customer['id'] ?? ($customer->getId() ?? null);
            $email = $customer['email'] ?? ($customer->getEmail() ?? null);
            $roles = $customer['roles'] ?? ($customer->getRoles() ?? []);
            if (is_string($roles)) {
                $roles = json_decode($roles, true);
            }
            return $this->json([
                'id' => $id,
                'email' => $email,
                'roles' => $roles,
            ]);
        } catch (\Throwable $e) {
            $logMsg = sprintf(
                "[%s] Account error: %s in %s:%d\nStack trace:\n%s\n",
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            );
            @file_put_contents(__DIR__ . '/../../../var/log/auth_errors.log', $logMsg, FILE_APPEND);
            return $this->json([
                'error' => 'Account lookup failed. Please contact support.',
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // To create an admin user, register a customer then update their roles to ["ROLE_ADMIN"] in the DB.
    // Or add a migration/utility for admin creation if needed.

}
