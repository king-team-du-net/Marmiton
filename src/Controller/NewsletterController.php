<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter-subscribe', name: 'newsletter_subscribe', methods: [Request::METHOD_GET])]
    public function subscribe(): void
    {
        // code...
    }
}
