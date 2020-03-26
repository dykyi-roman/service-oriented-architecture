<?php

declare(strict_types=1);

namespace App\Tests\Domain\Sender\Service;

use App\Domain\Sender\Service\MessageSenderFactory;
use App\Domain\Sender\Service\SentToProvider;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\TemplateLoader;
use App\Infrastructure\Cache\InMemoryCache;
use App\Infrastructure\Clients\NullClient;
use App\Infrastructure\Repository\InMemory\InMemoryTemplateRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @coversDefaultClass \App\Domain\Sender\Service\SentToProvider
 */
class SentToProviderTest extends WebTestCase
{
    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    /**
     * @covers ::execute
     */
    public function testSentToProvider(): void
    {
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
        $templateLoader = new TemplateLoader($templateLoaderRepository, $cache);

        $dicpatcher = $this->createMock(EventDispatcher::class);
        $dicpatcher->expects(self::once())->method('dispatch');

        $sender = new SentToProvider(
            $senderFactory,
            $templateLoader,
            $dicpatcher,
        );

        $sender->execute($this->createSendData());
    }

    private function createSendData(): \stdClass
    {
        $template = new \stdClass();
        $template->name = 'test-template';
        $template->lang = 'ua';
        $template->variables = [];

        $data = new \stdClass();
        $data->to = ['email' => $this->faker->email];
        $data->template = $template;
        $data->user_id = $this->faker->uuid;

        return $data;
    }
}
