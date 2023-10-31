<?php

namespace App\Controller\Dashboard\Shared;

use App\Controller\Controller;
use App\Entity\HasRoles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** MyProfile */
#[IsGranted(HasRoles::DEFAULT)]
class DashboardAccountController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/account', name: 'dashboard_account_index', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        $user = $this->getUserOrThrow();

        // $isActivity = '';

        return $this->render('dashboard/shared/account/index.html.twig', compact('user'));
    }
}
