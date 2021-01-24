<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Auth;

use App\Entity\User as UserEntity;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Cookie\JWTCookieProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class TokenSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $cookieProviders;

    protected $jwtManager;
    protected $dispatcher;

    /**
     * @param JWTTokenManagerInterface     $jwtManager
     * @param EventDispatcherInterface     $dispatcher
     * @param iterable|JWTCookieProvider[] $cookieProviders
     */
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        iterable $cookieProviders = []
    ) {
        $this->jwtManager = $jwtManager;
        $this->dispatcher = $dispatcher;
        $this->cookieProviders = $cookieProviders;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        return $this->handleAuthenticationSuccess($token->getUser());
    }

    public function handleAuthenticationSuccess(UserInterface $user, $jwt = null): Response
    {
        if (null === $jwt) {
            $jwt = $this->jwtManager->create($user);
        }

        $jwtCookies = [];
        foreach ($this->cookieProviders as $cookieProvider) {
            $jwtCookies[] = $cookieProvider->createCookie($jwt);
        }

        $response = new JWTAuthenticationSuccessResponse($jwt, [], $jwtCookies);

        if ($user instanceof UserEntity) {
            $userData = $user->toArray();
        } else {
            $userData = serialize($user);
        }

        $payload = [
            'token' => $jwt,
            'user' => $userData,
        ];

        $event = new AuthenticationSuccessEvent($payload, $user, $response);
        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);
        $responseData = $event->getData();

        if ($jwtCookies) {
            unset($responseData['token']);
        }

        if ($responseData) {
            $response->setData($responseData);
        } else {
            $response->setStatusCode(JWTAuthenticationSuccessResponse::HTTP_NO_CONTENT);
        }

        return $response;
    }
}
