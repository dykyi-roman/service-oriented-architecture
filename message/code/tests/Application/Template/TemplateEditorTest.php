<?php

declare(strict_types=1);

namespace App\Tests\Application\Template;

use App\Application\Template\TemplateEditor;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\ValueObject\Template;
use App\Infrastructure\Repository\InMemory\InMemoryTemplateRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Ramsey\Uuid\Uuid;

/**
 * @coversDefaultClass \App\Application\Template\TemplateEditor
 */
class TemplateEditorTest extends TestCase
{
    private const UUID = 'f9cb4bee-6ebe-11ea-bc55-0242ac130003';

    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    /**
     * @covers ::create
     */
    public function testCreate(): InMemoryTemplateRepository
    {
        $repository = new InMemoryTemplateRepository();
        $templateEditor = new TemplateEditor($repository, $repository, new NullLogger());
        $templateEditor->create(
            Uuid::fromString(self::UUID),
            new Template($this->faker->title, $this->faker->text),
            new MessageType(MessageType::TYPE_EMAIL),
            'test-name',
            'ua'
        );

        $this->assertCount(1, $repository->collection);

        return $repository;
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(InMemoryTemplateRepository $repository): void
    {
        $templateEditor = new TemplateEditor($repository, $repository, new NullLogger());
        $templateEditor->update(self::UUID, new Template('test-subject', 'test-context'));

        $this->assertCount(1, $repository->collection);
        $this->assertSame('test-subject', $repository->collection[self::UUID]['template']->getSubject());
        $this->assertSame('test-context', $repository->collection[self::UUID]['template']->getContext());
    }

    /**
     * @depends testCreate
     */
    public function testGetOne(InMemoryTemplateRepository $repository): void
    {
        $templateEditor = new TemplateEditor($repository, $repository, new NullLogger());
        $template = $templateEditor->getOneById(self::UUID);

        $this->assertInstanceOf(\App\Domain\Template\Document\Template::class, $template);
    }

    /**
     * @depends testCreate
     */
    public function testGetAll(InMemoryTemplateRepository $repository): void
    {
        $templateEditor = new TemplateEditor($repository, $repository, new NullLogger());
        $templates = $templateEditor->getAll();

        $this->assertCount(1, $repository->collection);
    }

    /**
     * @depends testCreate
     */
    public function testDelete(InMemoryTemplateRepository $repository): void
    {
        $templateEditor = new TemplateEditor($repository, $repository, new NullLogger());
        $templateEditor->delete(self::UUID);

        $this->assertCount(0, $repository->collection);
    }
}
