<?php

namespace App\Infrastructure\Doctrine\Event;

use App\Application\Interface\HasUserInterface;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * This subscriber makes sure the entities that need a user attached will get it
 */
class CurrentUserEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private Security $security) {}

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if(($entity instanceof HasUserInterface)
            && !$entity->getUser()
            && null !== $user = $this->security->getUser()
        ) {
            /** @var User $user */
            $entity->setUser($user);
        }
    }
}