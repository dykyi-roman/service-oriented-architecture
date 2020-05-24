<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Exception\UserException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class PasswordRestore
{
    private UserStore $userStore;
    private UserFinder $userFinder;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserFinder $userFinder, UserStore $userStore, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->userStore = $userStore;
        $this->userFinder = $userFinder;
    }

    /**
     * @throws UserException
     */
    public function restore(string $contact, string $password): void
    {
        $user = $this->userFinder->findByContact($contact);
        if (null === $user) {
            throw UserException::passwordRestore();
        }

        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setPlainPassword($password);
        $this->userStore->store($user);
    }
}
