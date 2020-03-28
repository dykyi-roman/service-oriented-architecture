<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\Doctrine\UserRepository")
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, JWTUserInterface
{
    private const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     */
    private string $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=500)
     */
    private string $password;

    /**
     * @var string|null
     */
    private string $plainPassword;

    /**
     * @ORM\Column(type="string", name="full_name", nullable=false)
     */
    private string $fullName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $phone;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     */
    private bool $isActive;

    public function __construct(UuidInterface $id, ?string $username = null, ?array $options = null)
    {
        $this->id = $id;
        $this->isActive = true;
        if (null !== $username) {
            $this->setEmail($username);
        }
        if (isset($options['email']) && null !== $options['email']) {
            $this->setEmail($options['email']);
        }
        if (isset($options['password']) && null !== $options['password']) {
            $this->setPassword($options['password']);
        }
    }

    public static function createFromPayload($username, array $payload)
    {
        return new self(
            Uuid::uuid4(),
            $username,
            $payload
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getRoles(): array
    {
        return [self::ROLE_USER];
    }
}
