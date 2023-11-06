<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subscribe;
use App\Form\SubscribeType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscribeController extends AbstractController
{
    #[Route('/subscribe-form', name: 'subscribe', methods: [Request::METHOD_GET])]
    public function index(Request $request, EmailService $email, EntityManagerInterface $em): Response
    {
        /** @var Subscribe $subscribe */
        $subscribe = new Subscribe();
        $form = $this->createForm(SubscribeType::class, $subscribe);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $subscribe = $form->getData();
                $em->persist($subscribe);
                $em->flush();
                $email->sendNewSubscriber($subscribe); // or something you do
                $referer = $request->headers->get('referer');
                return $this->redirect($referer); // return to previous page
            }
        }

        return $this->render('subscribe/index.html.twig', compact('form'));
    }
}
