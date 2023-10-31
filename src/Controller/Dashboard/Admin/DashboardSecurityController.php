<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardSecurityController extends AbstractController
{
    #[Route(path: '/%website_dashboard_path%/login', name: 'dashboard_security_login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function login(TranslatorInterface $translator, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('danger', $translator->trans('Already logged in'));

            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'translation_domain' => 'admin',
            'page_title' => $this->getParameter('website_name'),
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('dashboard_admin_index'),
            'username_label' => $translator->trans('E-mail address'),
            'password_label' => $translator->trans('Password'),
            'sign_in_label' => $translator->trans('Login'),
            'username_parameter' => 'email',
            'password_parameter' => 'password',
            'remember_me_enabled' => true,
            'remember_me_parameter' => '_remember_me',
            'remember_me_checked' => true,
            'remember_me_label' => $translator->trans('Remember me'),
        ]);
    }

    #[Route(path: '/%website_dashboard_path%/logout', name: 'dashboard_security_logout')]
    /** @codeCoverageIgnore */
    public function logout(): void
    {
    }
}
