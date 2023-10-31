<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Article;
use App\Entity\HasRoles;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/blog')]
class BlogLikeController extends AbstractController
{
    #[Route(path: '/like/article/{id}', name: 'blog_like_article', methods: [Request::METHOD_GET])]
    #[IsGranted(HasRoles::DEFAULT)]
    public function like(Article $article, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($article->isLikedByUser($user)) {
            $article->removeLike($user);
            $em->flush();

            return $this->json([
                'message' => $translator->trans('The like has been deleted.'),
                'nbLike' => $article->howManyLikes(),
            ]);
        }

        $article->addLike($user);
        $em->flush();

        return $this->json([
            'message' => $translator->trans('The like has been added.'),
            'nbLike' => $article->howManyLikes(),
        ]);
    }
}
