<?php

namespace App\Controller\Dashboard\Shared;

use App\Controller\Controller;
use App\Entity\HasRoles;
use App\Form\Update\AccountUpdatePasswordType;
use App\Form\Update\AccountUpdateSettingType;
use App\Form\Update\AccountUpdateSocialType;
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
class AccountSettingController extends Controller
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly EntityManagerInterface $em,
        private readonly LogoutUrlGenerator $logoutUrlGenerator
    ) {
    }

    #[Route(path: '/settings', name: 'settings', methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function settings(Request $request): Response
    {
        $user = $this->getUserOrThrow();

        // Profile processing
        [$formUpdateSettings, $response] = $this->accountUpdateSettings($request);
        if ($response) {
            return $response;
        }

        // Social media processing
        [$formUpdateSocial, $response] = $this->accountUpdateSocialMedia($request);
        if ($response) {
            return $response;
        }

        // Password processing
        /*
        [$formUpdatePassword, $response] = $this->accountUpdatePassword($request);
        if ($response) {
            return $response;
        }
        */

        return $this->render('dashboard/shared/account/setting/profile-manager.html.twig', compact(
            'formUpdateSettings',
            'formUpdateSocial',
            'user'
        ));
    }

    /**
     * Edit setting (profile) form.
     */
    private function accountUpdateSettings(Request $request): array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm(AccountUpdateSettingType::class, $user);
        if ('settings' !== $request->get('action')) {
            return [$form, null];
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('Your personal information has been successfully updated.'));

            return [$form, $this->redirectToRoute('dashboard_account_index')];
        }

        return [$form, null];
    }

    /**
     * Edit social media form.
     */
    private function accountUpdateSocialMedia(Request $request): array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm(AccountUpdateSocialType::class, $user);
        if ('social' !== $request->get('action')) {
            return [$form, null];
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('Your social media has been successfully updated.'));

            return [$form, $this->redirectToRoute('dashboard_account_index')];
        }

        return [$form, null];
    }

    /*
     * Edit password form.
     */
    /*
    private function accountUpdatePassword(Request $request): array
    {
        $form = $this->createForm(AccountUpdatePasswordType::class);
        if ('plainPassword' !== $request->get('action')) {
            return [$form, null];
        }

        $user = $this->getUserOrThrow();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->hasher->hashPassword($user, $form->get("plainPassword")->getData()));
            $user->setResetPasswordToken(null);
            $this->em->flush();
            $this->addFlash('success', $this->translator->trans('Your password has been successfully updated.'));

            return [$form, $this->redirect($this->logoutUrlGenerator->getLogoutPath())];
        }

        return [$form, null];
    }
    */
}
