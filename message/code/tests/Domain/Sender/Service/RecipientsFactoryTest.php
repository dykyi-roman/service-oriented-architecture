<?php

declare(strict_types=1);

namespace App\Tests\Domain\Sender\Service;

use App\Domain\Sender\Exception\MessageException;
use App\Domain\Sender\Service\RecipientsFactory;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Sender\ValueObject\Recipients;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Domain\Sender\Service\RecipientsFactory
 */
final class RecipientsFactoryTest extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->facker = Factory::create();
    }

    public function dataProviderMessageType(): ?\Generator
    {
        yield 'phone message type' => [MessageType::TYPE_PHONE, '+380938982443'];
        yield 'email message type' => [MessageType::TYPE_EMAIL, 'mr.dukuy@gmail.com'];
    }

    /**
     * @dataProvider dataProviderMessageType
     * @covers ::create
     */
    public function testCreateRecipientSuccess(string $type, $value): void
    {
        $_ENV['SENDER_PHONE_NUMBER'] = $this->facker->e164PhoneNumber;
        $_ENV['SENDER_EMAIL_ADDRESS'] = $this->facker->email;
        $factory = new RecipientsFactory(new MessageType($type));
        $recipient = $factory->create($value);

        $this->assertInstanceOf(Recipients::class, $recipient);
    }

    public function testCreateRecipientWithNotExistType(): void
    {
        $this->expectException(MessageException::class);

        $_ENV['SENDER_PHONE_NUMBER'] = $this->facker->e164PhoneNumber;
        $_ENV['SENDER_EMAIL_ADDRESS'] = $this->facker->email;
        $factory = new RecipientsFactory(new MessageType('test-type'));
        $factory->create('some value');
    }
}