<?php

namespace App\Controller\Dashboard\Shared;

use App\Controller\Controller;
use App\Entity\HasRoles;
use App\Form\Update\AccountUpdateAddressType;
use App\Form\Update\AccountUpdatePersonalType;
use App\Form\Update\AccountUpdateSocialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(HasRoles::DEFAULT)]
#[Route(path: '/%website_dashboard_path%/account', name: 'dashboard_account_')]
class AccountPersonalController extends Controller
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $em,
    ) {
    }

    #[Route(path: '/personals', name: 'personals', methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function personals(Request $request): Response
    {
        $user = $this->getUserOrThrow();

        // Personals processing
        [$formUpdatePersonals, $response] = $this->accountUpdatePersonals($request);
        if ($response) {
            return $response;
        }

        // Social media processing
        [$formUpdateSocial, $response] = $this->accountUpdateSocialMedia($request);
        if ($response) {
            return $response;
        }

        // Address processing
        /*
        [$formUpdateAddress, $response] = $this->accountUpdateAddress($request);
        if ($response) {
            return $response;
        }
        */

        return $this->render('dashboard/shared/account/personal-manager.html.twig', compact(
            'formUpdatePersonals',
            'formUpdateSocial',
            // 'formUpdateAddress',
            'user'
        ));
    }

    /**
     * Edit personal (profile) form.
     */
    private function accountUpdatePersonals(Request $request): array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm(AccountUpdatePersonalType::class, $user);
        if ('personals' !== $request->get('action')) {
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
     * Edit address form.
     */
    /*
    private function accountUpdateAddress(Request $request): array
    {
        $user = $this->getUserOrThrow();
        $form = $this->createForm(AccountUpdateAddressType::class, $user);
        if ('address' !== $request->get('action')) {
            return [$form, null];
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('Your address has been successfully updated.'));

            return [$form, $this->redirectToRoute('dashboard_account_index')];
        }

        return [$form, null];
    }
    */
}
