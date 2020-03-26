<?php

declare(strict_types=1);

namespace App\Tests\Domain\Sender\Listener;

use App\Domain\Sender\Event\NotSentEvent;
use App\Domain\Sender\Event\SentEvent;
use App\Domain\Sender\Listener\PersistMessageSubscriber;
use App\Domain\Template\ValueObject\Template;
use App\Infrastructure\Repository\InMemory\InMemoryNotSentRepository;
use App\Infrastructure\Repository\InMemory\InMemorySentRepository;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @coversDefaultClass \App\Domain\Sender\Listener\PersistMessageSubscriber
 */
class PersistMessageSubscriberTest extends WebTestCase
{
    /**
     * @covers ::sent
     */
    public function testSent(): void
    {
        $faker = Factory::create();
        $template = new Template($faker->title, $faker->randomHtml());
        $event = new SentEvent(Uuid::uuid4()->toString(), $template);

        $sentRepository = new InMemorySentRepository();
        $notSentRepository = new InMemoryNotSentRepository();

        $subscriber = new PersistMessageSubscriber($sentRepository, $notSentRepository);
        $subscriber->sent($event);

        $this->assertCount(1, $sentRepository->collection);
    }

    /**
     * @covers ::notSent
     */
    public function testNotSent(): void
    {
        $faker = Factory::create();
        $template = new Template($faker->title, $faker->randomHtml());
        $event = new NotSentEvent(Uuid::uuid4()->toString(), $template, $faker->text);


        $sentRepository = new InMemorySentRepository();
        $notSentRepository = new InMemoryNotSentRepository();
        $subscriber = new PersistMessageSubscriber($sentRepository, $notSentRepository);
        $subscriber->notSent($event);

        $this->assertCount(1, $notSentRepository->collection);
    }
}
