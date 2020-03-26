<?php

declare(strict_types=1);

namespace App\Tests\Domain\Template;

use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\TemplateLoader;
use App\Domain\Template\ValueObject\Template;
use App\Infrastructure\Cache\InMemoryCache;
use App\Infrastructure\Repository\InMemory\InMemoryTemplateRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @coversDefaultClass \App\Domain\Template\TemplateLoader
 */
class TemplateLoaderTest extends WebTestCase
{
    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    /**
     * @covers ::load
     */
    public function testLoadTemplateSuccess(): void
    {
        $templateRepository = new InMemoryTemplateRepository();
        $templateRepository->create(
            'test-uuid',
            'test-template',
            MessageType::TYPE_PHONE,
            'en',
            $this->faker->title,
            $this->faker->text
        );

        $cache = new InMemoryCache();
        $templateLoader = new TemplateLoader($templateRepository, $cache);
        $template = $templateLoader->load($this->createTemplate(), new MessageType(MessageType::TYPE_PHONE));

        $this->assertCount(1, $cache->data);
        $this->assertInstanceOf(Template::class, $template);
    }

    /**
     * @covers ::load
     */
    public function testLoadTemplateFailed(): void
    {
        $this->expectException(TemplateException::class);

        $templateRepository = new InMemoryTemplateRepository();
        $templateRepository->create(
            'test-uuid',
            'not-exist-template',
            MessageType::TYPE_PHONE,
            'ru',
            $this->faker->title,
            $this->faker->text
        );

        $cache = new InMemoryCache();
        $templateLoader = new TemplateLoader($templateRepository, $cache);
        $templateLoader->load($this->createTemplate(), new MessageType(MessageType::TYPE_PHONE));

        $this->assertCount(0, $cache->data);
    }

    private function createTemplate(): \stdClass
    {
        $template = new \stdClass();
        $template->name = 'test-template';
        $template->lang = 'en';
        $template->variables = [];

        return $template;
    }
}
