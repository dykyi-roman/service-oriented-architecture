<?php

declare(strict_types=1);

namespace App\Tests\Domain\Sender\Service;

use App\Domain\Sender\Exception\MessageException;
use App\Domain\Sender\MessageSenderInterface;
use App\Domain\Sender\Service\MessageSenderFactory;
use App\Domain\Sender\ValueObject\MessageType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @coversDefaultClass \App\Domain\Sender\Service\MessageSenderFactory
 */
final class MessageSenderFactoryTest extends WebTestCase
{
    public function dataProviderMessageType(): ?\Generator
    {
        yield 'phone message type' => [MessageType::TYPE_PHONE];
        yield 'email message type' => [MessageType::TYPE_EMAIL];
    }

    /**
     * @inheritDoc
     * @dataProvider dataProviderMessageType
     * @covers ::create
     */
    public function testCreateMessageSenderSuccess(string $type): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $factory = new MessageSenderFactory($container);

        $sender = $factory->create(new MessageType($type));

        $this->assertInstanceOf(MessageSenderInterface::class, $sender);
    }

    /**
     * @covers ::create
     */
    public function testCreateMessageSenderWithNotExistType(): void
    {
        $this->expectException(MessageException::class);

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $factory = new MessageSenderFactory($container);

        $factory->create(new MessageType('test-type'));
    }
}
