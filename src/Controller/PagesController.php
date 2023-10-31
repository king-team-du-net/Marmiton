<?php

namespace App\Controller;

use App\Entity\HasRoles;
use App\Entity\Pages\Contact;
use App\Entity\Pages\Page;
use App\Entity\Setting\Setting;
use App\Entity\User;
use App\Form\Pages\ContactType;
use App\Repository\Pages\FaqRepository;
use App\Repository\TestimonialRepository;
use App\Repository\UserRepository;
use App\Service\ContactService;
use App\Service\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/page')]
class PagesController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route(path: '/contact', name: 'contact', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function contact(
        Request $request,
        ContactService $contactService,
        SettingService $settingService
    ): Response {
        $contact = new Contact();

        if ($this->isGranted(HasRoles::DEFAULT)) {
            /** @var User $user */
            $user = $this->getUser();
            $contact
                ->setFullName($user->getFullName())
                ->setEmail($user->getEmail())
            ;
        }

        $form = $this->createForm(ContactType::class, $contact);

        if ('no' === $settingService->getValue(Setting::GOOGLE_RECAPTCHA_ENABLED)) {
            $form->remove('recaptcha');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contactService->persistContact($contact);

            $this->addFlash('success', $this->translator->trans('Your message has been successfully sent, thank you.'));

            return $this->redirectToRoute('contact');
        }

        return $this->render('pages/contact.html.twig', compact('form', 'contact'));
    }

    #[Route(path: '/access-denied', name: 'access_denied', methods: [Request::METHOD_GET])]
    public function accessDenied(): Response
    {
        return $this->render('pages/access-denied.html.twig');
    }

    #[Route(path: '/team', name: 'team', methods: [Request::METHOD_GET])]
    public function about(UserRepository $userRepository): Response
    {
        $teams = $userRepository->findTeam(6);

        if (!$teams) {
            $this->addFlash('danger', $this->translator->trans('The team can not be found'));

            return $this->redirectToRoute('home');
        }

        return $this->render('pages/team-detail.html.twig', compact('teams'));
    }

    #[Route('/faq-rules', name: 'faq', methods: [Request::METHOD_GET])]
    public function faq(FaqRepository $faqRepository): Response
    {
        $faqs = $faqRepository->findAll();

        if (!$faqs) {
            $this->addFlash('danger', $this->translator->trans('The faq can not be found'));

            return $this->redirectToRoute('home');
        }

        return $this->render('pages/faq-detail.html.twig', compact('faqs'));
    }

    #[Route('/testimonial', name: 'testimonial', methods: [Request::METHOD_GET])]
    public function testimonial(TestimonialRepository $testimonialRepository): Response
    {
        $testimonials = $testimonialRepository->findLastRecent(12);

        if (!$testimonials) {
            $this->addFlash('danger', $this->translator->trans('The testimonial can not be found'));

            return $this->redirectToRoute('home');
        }

        return $this->render('pages/testimonial-detail.html.twig', compact('testimonials'));
    }

    #[Route(path: '/{slug}', name: 'page', methods: [Request::METHOD_GET])]
    public function pages(Page $page): Response
    {
        if (!$page) {
            $this->addFlash('danger', $this->translator->trans('The page can not be found'));

            return $this->redirectToRoute('home');
        }

        return $this->render('pages/page-detail.html.twig', [
            'entity' => $page,
        ]);
    }
}
