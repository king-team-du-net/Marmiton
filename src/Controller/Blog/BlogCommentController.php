<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Article;
use App\Entity\Blog\Comment;
use App\Entity\HasRoles;
use App\Entity\User;
use App\Events\Article\CommentCreatedEvent;
use App\Form\Blog\ArticleCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/blog')]
class BlogCommentController extends AbstractController
{
    #[Route(path: '/comment/{slug}/add', name: 'blog_comment_add', methods: [Request::METHOD_POST])]
    #[IsGranted('IS_AUTHENTICATED')]
    // #[IsGranted(HasRoles::DEFAULT)]
    public function add(
        // #[CurrentUser] User $user,
        Request $request,
        RequestStack $requestStack,
        Article $article,
        // #[MapEntity(mapping: ['articleSlug' => 'slug'])] Article $article,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator
    ): Response {
        // Find recent comments approved
        // $comments = $article->getIsApprovedComments();

        /** @var User $user */
        $user = $this->getUser();

        // Create a new comment
        $comment = new Comment();
        $comment
            ->setIsApproved(false)
            ->setIsRGPD(true)
            ->setAuthor($user)
            ->setIp($requestStack->getMainRequest()?->getClientIp())
            // ->setArticle($article)
        ;

        /*
        if($this->getUser()) {
            $comment->setAuthor($user);
        }
        */

        $article->addComment($comment);

        $form = $this->createForm(ArticleCommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));

            $this->addFlash('success', $translator->trans('Your comment has been sent, thank you. It will be published after validation by a moderator.'));

            return $this->redirectToRoute('blog_show', ['slug' => $article->getSlug()]);
        }

        return $this->render('blog/comment/form-error.html.twig', compact('article', 'form'));
    }

    public function form(Article $article): Response
    {
        $form = $this->createForm(ArticleCommentType::class);

        return $this->render('blog/comment/_form.html.twig', compact('article', 'form'));
    }

    #[Route(path: '/comment/{id}', name: 'blog_comment_delete', methods: [Request::METHOD_POST])]
    // #[Security("is_granted('ROLE_USER') and user === comment.getAuthor()")]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $params = ['slug' => $comment->getArticle()->getSlug()];
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
        }

        $this->addFlash('success', $translator->trans('Your comment has been deleted.'));

        return $this->redirectToRoute('blog_show', $params);
    }
}
