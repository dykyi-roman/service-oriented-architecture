<?php

declare(strict_types=1);

namespace App\Application\Listeners;

use App\Domain\Document\NotSent;
use App\Domain\Document\Sent;
use App\Domain\Event\NotSentEvent;
use App\Domain\Event\SentEvent;
use App\Domain\Repository\NotSentRepositoryInterface;
use App\Domain\Repository\SentRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistMessageSubscriber implements EventSubscriberInterface
{
    private SentRepositoryInterface $sentRepository;
    private NotSentRepositoryInterface $notSentRepository;

    public function __construct(SentRepositoryInterface $sentRepository, NotSentRepositoryInterface $notSentRepository)
    {
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
