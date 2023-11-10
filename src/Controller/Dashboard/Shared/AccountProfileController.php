<?php

namespace App\Controller\Dashboard\Shared;

use App\Controller\Controller;
use App\Entity\HasRoles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/** MyProfile */
#[IsGranted(HasRoles::DEFAULT)]
#[Route(path: '/%website_dashboard_path%/account', name: 'dashboard_account_')]
class AccountProfileController extends Controller
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $em,
    ) {
    }

    #[Route(path: '/profile', name: 'profile', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function profile(Request $request): Response
    {
        $user = $this->getUserOrThrow();

        // $isActivity = '';

        return $this->render('dashboard/shared/account/profile-manager.html.twig', compact(
            'user'
        ));
    }
}
