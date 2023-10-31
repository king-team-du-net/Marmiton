<?php

namespace App\Controller\Dashboard\Main;

use App\Entity\HasRoles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DashboardController extends AbstractController
{
    #[Route('/%website_dashboard_path%', name: 'dashboard_index')]
    public function index(AuthorizationCheckerInterface $authChecker): Response
    {
        if ($authChecker->isGranted(HasRoles::SUPERADMIN, HasRoles::MODERATOR)) {
            return $this->redirectToRoute('dashboard_admin_index');
        } elseif ($authChecker->isGranted(HasRoles::DEFAULT)) {
            return $this->redirectToRoute('dashboard_account_index');
        }

        return $this->redirectToRoute('security_login');
    }
}
