<?php

declare(strict_types=1);

use App\Domain\Entity\User;
use App\Domain\Transformer\Api\UserApiTransformer;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @coversDefaultClass UserApiTransformer
 */
class UserApiTransformerTest extends WebTestCase
{
    /**
     * @covers ::transform
     */
    public function testUserTransform(): void
    {
        $fullName = 'Dikiy Roman';
        $phone = '+380938982443';
        $email = 'test@gmail.com';

        $user = new User(Uuid::uuid4());
        $user->setFullName($fullName);
        $user->setPhone($phone);
        $user->setEmail($email);

        $this->assertEquals([
            'fullName' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'isActive' => true,
        ], UserApiTransformer::transform($user));
    }
}
