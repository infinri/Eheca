<?php

namespace Eheca\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class LoginFormAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_login'
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        return new SelfValidatingPassport(
            new UserBadge($email, function($userIdentifier) {
                // This would be where you load or create the user
                // For now, we'll just return a basic user
                return new \Symfony\Component\Security\Core\User\User(
                    $userIdentifier,
                    null,
                    ['ROLE_USER']
                );
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirect to the homepage on success
        return new \Symfony\Component\HttpFoundation\RedirectResponse('/');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Redirect back to the login page with an error
        $request->getSession()->set(
            \Symfony\Component\Security\Core\Security::AUTHENTICATION_ERROR,
            $exception
        );
        
        return new \Symfony\Component\HttpFoundation\RedirectResponse('/login');
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new \Symfony\Component\HttpFoundation\RedirectResponse('/login');
    }
}
