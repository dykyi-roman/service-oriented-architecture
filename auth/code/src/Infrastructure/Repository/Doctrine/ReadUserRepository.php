<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\ReadUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReadUserRepository extends ServiceEntityRepository implements ReadUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findActiveUserByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email, 'isActive' => true]);
    }

    public function findActiveUserById(string $userId): ?User
    {
        return $this->findOneBy(['id' => $userId, 'isActive' => true]);
    }

    public function findUserById(string $userId): ?User
    {
        return $this->findOneBy(['id' => $userId]);
    }

    public function all(): array
    {
        return $this->findAll();
    }

    public function findUserByEmailOrPhone(string $contact): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')->setParameter('email', $contact)
            ->orWhere('u.phone = :phone')->setParameter('phone', $contact)->getQuery()
            ->getOneOrNullResult();
    }
}
