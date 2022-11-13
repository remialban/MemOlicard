<?php

namespace App\EventListener;

use Doctrine\ORM\Events;
use App\Service\TypeSense\TypeSense;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class TypeSenseEventListener implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    private TypeSense $typesense;

    public function __construct(LoggerInterface $logger, TypeSense $typesense)
    {
        $this->logger = $logger;
        $this->typesense = $typesense;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        // $object = $args->getObject();
        // if ($object instanceof User)
        // {
        //     $this->typesense->update($object);
        // }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        // $object = $args->getObject();
        // if ($object instanceof User)
        // {
        //     $this->typesense->update($object);
        // }
    }
}
