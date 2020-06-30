<?php

declare(strict_types=1);

namespace App\Tests\Domain\Message\Service;

use App\Application\Sender\Message\Message;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Message\Service\MessageAdapter;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @coversDefaultClass MessageAdapter::class
 */
final class MessageAdapterTest extends TestCase
{
    /**
     * @covers ::sendRegistrationSuccessMessage
     */
    public function testSendRegistrationSuccessMessage(): void
    {
        $faker = Factory::create();
        $message = new Envelope(new Message($faker->uuid, [], []));
        $busMock = $this->createMock(MessageBusInterface::class);
        $busMock->expects(self::once())->method('dispatch')->willReturn($message);

        $messageAdapter = new MessageAdapter($busMock);
        $messageAdapter->sendRegistrationSuccessMessage(
            Uuid::fromString($faker->uuid),
            new Email($faker->email)
        );
    }

    /**
     * @covers ::sendPasswordForgotCodeMessage
     */
    public function testSendPasswordForgotCodeMessage(): void
    {
        $faker = Factory::create();
        $message = new Envelope(new Message($faker->uuid, [], []));
        $busMock = $this->createMock(MessageBusInterface::class);
        $busMock->expects(self::once())->method('dispatch')->willReturn($message);

        $messageAdapter = new MessageAdapter($busMock);
        $messageAdapter->sendPasswordForgotCodeMessage($faker->email, '12345');
    }
}
