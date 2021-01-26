<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscriber\User;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\EventSubscriber\AbstractEventSubscriber;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserUpdateEventSubscriber extends AbstractEventSubscriber
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

        return $this;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                'updatePassword',
                EventPriorities::PRE_WRITE,
            ],
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function updatePassword(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (\in_array(strtoupper($method), ['PUT', 'POST'], true)) {
            if ($user instanceof User) {
                if (null !== $user->getPlainPassword()) {
                    $password = $this->passwordEncoder->encodePassword(
                        $user,
                        $user->getPlainPassword()
                    );

                    $user->setPassword($password);
                }
            }
        }
    }
}
