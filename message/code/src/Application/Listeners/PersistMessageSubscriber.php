<?php

declare(strict_types=1);

namespace App\Application\Listeners;

use App\Domain\Document\NotSent;
use App\Domain\Document\Sent;
use App\Domain\Event\NotSentEvent;
use App\Domain\Event\SentEvent;
use App\Domain\Repository\NotSentPersistRepositoryInterface;
use App\Domain\Repository\SentPersistRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @todo When storage becomes a bottleneck, you always could to replace repository interface to queue interface /
 *       and push the data in the next queue sent and not sent.
 */
class PersistMessageSubscriber implements EventSubscriberInterface
{
    private SentPersistRepositoryInterface $sentRepository;
    private NotSentPersistRepositoryInterface $notSentRepository;

    public function __construct(
        SentPersistRepositoryInterface $sentRepository,
        NotSentPersistRepositoryInterface $notSentRepository
    ) {
        $this->sentRepository = $sentRepository;
        $this->notSentRepository = $notSentRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            SentEvent::class => 'sent',
            NotSentEvent::class => 'notSent',
        ];
    }

    public function sent(SentEvent $event): void
    {
        $sent = (new Sent($event->getUserId()))->setTemplate($event->getTemplate()->toJson());
        $this->sentRepository->save($sent);
    }

    public function notSent(NotSentEvent $event): void
    {
        $template = $event->getTemplate() ? $event->getTemplate()->toJson() : '';
        $notSent = (new NotSent($event->getUserId()))->setError($event->getError())->setTemplate($template);

        $this->notSentRepository->save($notSent);
    }
}
