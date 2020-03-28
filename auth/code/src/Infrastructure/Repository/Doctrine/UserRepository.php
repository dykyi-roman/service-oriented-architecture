<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->encoder = $encoder;
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email, 'isActive' => true]);
    }

    public function findUserById(string $userId): ?User
    {
        return $this->findOneBy(['id' => $userId, 'isActive' => true]);
    }

    /**
     * @inheritDoc
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createUser(
        UuidInterface $uuid,
        string $email,
        string $password,
        string $phone,
        string $fullName
    ): void {
        $em = $this->getEntityManager();
        $user = new User($uuid);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setFullName($fullName);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush();
    }
}
