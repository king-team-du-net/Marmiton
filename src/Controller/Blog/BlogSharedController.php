<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Article;
use App\Entity\Setting\Setting;
use App\Form\Blog\ArticleSharedType;
use App\Service\SendMailService;
use App\Service\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogSharedController extends AbstractController
{
    #[Route(
        path: '/blog/{slug}/shared',
        name: 'blog_article_shared',
        methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function shared(
        Request $request,
        Article $article,
        SettingService $settingService,
        SendMailService $mail,
        TranslatorInterface $translator
    ): Response {
        if (!$article) {
            $this->addFlash('danger', $translator->trans('The article not be found'));

            return $this->redirectToRoute('blog');
        }

        $appErrors = [];

        $form = $this->createForm(ArticleSharedType::class);

        if ('no' === $settingService->getValue(Setting::GOOGLE_RECAPTCHA_ENABLED)) {
            $form->remove('recaptcha');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $subject = sprintf('%s recommends you to read "%s"', $data['sender_name'], $article->getTitle());

            $mail->send(
                $data['receiver_email'],
                $subject,
                'article-detail-shared',
                [
                    'article' => $article,
                    'sender_name' => $data['sender_name'],
                    'sender_comments' => $data['sender_comments'],
                ],
            );

            $this->addFlash('success', $translator->trans('🚀 Article successfully shared with your friend!'));

            return $this->redirectToRoute('blog');
        } elseif ($form->isSubmitted()) {
            /** @var FormError $error */
            foreach ($form->getErrors() as $error) {
                if (null === $error->getCause()) {
                    $appErrors[] = $error;
                }
            }
        }

        return $this->render('blog/article-detail-shared.html.twig', [
            'errors' => $appErrors,
            'article' => $article,
            'form' => $form,
        ]);
    }
}
