<?php

declare(strict_types=1);

namespace App\Tests\Application\Sender\Service;

use App\Application\Sender\Service\ProviderSender;
use App\Domain\Sender\Service\MessageSenderFactory;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\TemplateLoader;
use App\Infrastructure\Cache\InMemoryCache;
use App\Infrastructure\Clients\NullClient;
use App\Infrastructure\Metrics\InMemoryMetrics;
use App\Infrastructure\Repository\InMemory\InMemoryTemplateRepository;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @coversDefaultClass \App\Application\Sender\Service\ProviderSender
 */
class ProviderSenderTest extends WebTestCase
{
    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    /**
     * @covers ::send
     */
    public function testSentToProvider(): void
    {
        $_ENV['SENDER_EMAIL_ADDRESS'] = 'test@gmail.com';
        $senderFactory = $this->createMock(MessageSenderFactory::class);
        $senderFactory->expects(self::once())->method('create')->willReturn(new NullClient());

        $templateLoaderRepository = new InMemoryTemplateRepository();
        $templateLoaderRepository->create(
            'test-uuid',
            'test-template',
            MessageType::TYPE_EMAIL,
            'ua',
            $this->faker->title,
            $this->faker->randomHtml()
        );

        $cache = new InMemoryCache();
        $templateLoader = new TemplateLoader($templateLoaderRepository, $cache, new InMemoryMetrics());

        self::bootKernel();
        $transport = self::$container->get('messenger.bus.default');
        $sender = new ProviderSender(
            $senderFactory,
            $templateLoader,
            $transport,
            new InMemoryMetrics(),
            new NullLogger()
        );

        $sender->send($this->createSendData());
    }

    private function createSendData(): \stdClass
    {
        $template = new \stdClass();
        $template->name = 'test-template';
        $template->lang = 'ua';
        $template->variables = [];

        $data = new \stdClass();
        $data->recipients = ['email' => $this->faker->email];
        $data->template = $template;
        $data->userId = $this->faker->uuid;

        return $data;
    }
}
