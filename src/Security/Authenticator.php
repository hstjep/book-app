<?php

namespace App\Security;

use App\Common\Constants;
use App\Service\SecurityServiceInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Authenticator extends AbstractLoginFormAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        protected SecurityServiceInterface $securityService,
        protected UrlGeneratorInterface $urlGenerator
    ) {
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool
    {
        return ($request->get("_email") !== null && $request->get("_password")) || $request->get("_tok");
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->get('_email');
        $password = $request->get('_password');

        try {
            $user = $this->securityService->authenticate($email, $password);

            if ($user === null) {
                throw new CustomUserMessageAuthenticationException('Invalid credentials');
            }

            $userBadge = new UserBadge($user->getEmail());
            $userBadge->setUserLoader(function () use ($user) {
                return $user;
            });

            return new SelfValidatingPassport($userBadge);
        } catch (\Exception $ex) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $request->getSession()->set(Security::LAST_USERNAME, $token->getUser()->getEmail());

        // Store the user token in the session
        $request->getSession()->set(Constants::EXTERNAL_API_TOKEN, $token->getUser()->getApiToken());

        // Redirect to a success route
        return new RedirectResponse(
            $this->urlGenerator->generate('home_index')
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse(
            $this->urlGenerator->generate('app_login')
        );
    }
}
