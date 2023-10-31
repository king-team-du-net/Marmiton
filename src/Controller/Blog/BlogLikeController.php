<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Article;
use App\Entity\HasRoles;
use App\Entity\User\Customer;
use App\Entity\User\Editor;
use App\Entity\User\Leader;
use App\Entity\User\Writer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/blog')]
class BlogLikeController extends AbstractController
{
    #[Route(path: '/like/article/{id}', name: 'blog_like_article', methods: [Request::METHOD_GET])]
    #[IsGranted(HasRoles::DEFAULT)]
    public function like(Article $article, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        /** @var Leader|Editor|Writer|Customer|User $user */
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
