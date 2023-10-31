<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Pages\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ContactService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $requestStack
    ) {
    }

    public function persistContact(Contact $contact): void
    {
        $contact->setIp($this->requestStack->getMainRequest()?->getClientIp());
        $contact->setIsSend(false);
        $contact->setCreatedAt(new \DateTime('now'));

        $this->em->persist($contact);
        $this->em->flush();
    }

    public function isSend(Contact $contact): void
    {
        $contact->setIsSend(true);

        $this->em->persist($contact);
        $this->em->flush();
    }
}
