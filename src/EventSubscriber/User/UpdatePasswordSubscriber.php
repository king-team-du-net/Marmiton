<?php

namespace App\EventSubscriber\User;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdatePasswordSubscriber implements EventSubscriberInterface
{
    public function __construct(protected readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function onBeforeEntityPersisted(BeforeEntityUpdatedEvent|BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof User) {
            return;
        }

        if (!is_null($entity->getPlainPassword()) && '' != $entity->getPlainPassword()) {
            $entity->setPassword(
                $this->hasher->hashPassword($entity, $entity->getPlainPassword())
            );
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersisted',
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityPersisted',
        ];
    }
}
