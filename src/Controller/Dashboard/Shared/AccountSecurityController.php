<?php

namespace App\Controller\Dashboard\Shared;

use App\Controller\Controller;
use App\Entity\HasRoles;
use App\Form\Update\AccountUpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(HasRoles::DEFAULT)]
#[Route(path: '/%website_dashboard_path%/account', name: 'dashboard_account_')]
class AccountSecurityController extends Controller
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly EntityManagerInterface $em,
        private readonly LogoutUrlGenerator $logoutUrlGenerator
    ) {
    }

    #[Route(path: '/security', name: 'security', methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function security(Request $request): Response
    {
        $user = $this->getUserOrThrow();

        // Password processing
        [$formUpdatePassword, $response] = $this->accountUpdatePassword($request);
        if ($response) {
            return $response;
        }

        // Social accounts processing

        return $this->render('dashboard/shared/account/security-manager.html.twig', compact(
            'formUpdatePassword',
            'user'
        ));
    }

    /*
     * Edit password form.
     */
    private function accountUpdatePassword(Request $request): array
    {
        $form = $this->createForm(AccountUpdatePasswordType::class);
        if ('plainPassword' !== $request->get('action')) {
            return [$form, null];
        }

        $user = $this->getUserOrThrow();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->hasher->hashPassword($user, $form->get('plainPassword')->getData()));
            // $user->setResetPasswordToken(null);
            $this->em->flush();
            $this->addFlash('success', $this->translator->trans('Your password has been successfully updated.'));

            return [$form, $this->redirect($this->logoutUrlGenerator->getLogoutPath())];
        }

        return [$form, null];
    }

    /*
     * Edit social accounts form.
     */
}
