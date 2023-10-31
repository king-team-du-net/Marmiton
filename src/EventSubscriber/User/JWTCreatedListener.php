<?php

namespace App\EventSubscriber\User;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: "lexik_jwt_authentication.on_jwt_created", method: 'onJWTCreated')]
final class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['email'] = $user->getUserIdentifier();
        $payload['countUserRecipes'] = count($user->getUserRecipes());

        $event->setData($payload);
    }
}