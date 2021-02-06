<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventListeners;

use App\Entity\Listener\AbstractEntityListener;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JWTCreatedListener extends AbstractEntityListener
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * Class constructor.
     *
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        UserProviderInterface $userProvider
    ) {
        $this->userProvider = $userProvider;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();

        if (\array_key_exists('username', $payload)) {
            $user = $this->userProvider->loadUserByUsername($payload['username']);

            if (User::class === \get_class($user)) {
                $payload['email'] = $user->getEmail();
            }
        }

        $event->setData($payload);
    }
}
