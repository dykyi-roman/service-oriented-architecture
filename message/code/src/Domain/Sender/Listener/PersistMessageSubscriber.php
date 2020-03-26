<?php

declare(strict_types=1);

namespace App\Domain\Sender\Listener;

use App\Domain\Sender\Event\NotSentEvent;
use App\Domain\Sender\Event\SentEvent;
use App\Domain\Sender\Document\Sent;
use App\Domain\Sender\Document\NotSent;
use App\Domain\Sender\Repository\NotSentPersistRepositoryInterface;
use App\Domain\Sender\Repository\SentPersistRepositoryInterface;
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
