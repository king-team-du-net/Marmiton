<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ReviewRepository;
use App\Repository\Blog\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route(path: '/profile/@{slug}', name: 'user_profil', methods: [Request::METHOD_GET])]
    public function profil(
        User $user,
        CommentRepository $commentRepository,
        ReviewRepository $reviewRepository
    ): Response {
        $lastComments = $commentRepository->findLastByUser($user, 4);
        $lastReviews = $reviewRepository->findLastByUser($user, 4);

        return $this->render('user/profile-manager.html.twig', compact('user', 'lastComments', 'lastReviews'));
    }
}
