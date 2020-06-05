<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\WriteUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class WriteUserRepository extends ServiceEntityRepository implements WriteUserRepositoryInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->encoder = $encoder;
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
        $user = new User($uuid);
        $user->setRoles([User::ROLE_USER]);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setFullName($fullName);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $this->store($user);
    }

    /**
     * @inheritDoc
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createAdmin(UuidInterface $uuid, string $email, string $password, string $fullName): void
    {
        $user = new User($uuid);
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setEmail($email);
        $user->setFullName($fullName);
        $user->setPhone('+000000000000');
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $this->store($user);
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
