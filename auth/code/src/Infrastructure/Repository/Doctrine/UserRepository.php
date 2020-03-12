<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface, UserLoaderInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->encoder = $encoder;
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @inheritDoc
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createUser(string $email, string $password, string $username): void
    {
        $em = $this->getEntityManager();
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush();
    }

    public function loadUserByUsername(string $usernameOrEmail = null)
    {
        return $this->findOneBy(['email' => $usernameOrEmail]);
    }
}
