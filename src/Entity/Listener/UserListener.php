<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Listener;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener extends AbstractEntityListener
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
     * @param User $user
     */
    public function preUpdate(User $user)
    {
        $this->updatePassword($user);
    }

    /**
     * @param User $user
     */
    public function prePersist(User $user)
    {
        $this->updatePassword($user);
    }

    private function updatePassword(User $user)
    {
        if (null !== $user->getPlainPassword()) {
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
        }
    }
}
