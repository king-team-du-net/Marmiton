<?php

namespace App\Controller;

use App\Entity\Recipe\Recipe;
use App\Repository\ReviewRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReviewController extends Controller
{
    #[Route(path: '/recipe/{slug}/reviews', name: 'recipe_reviews', methods: [Request::METHOD_GET])]
    public function recipereviews(
        Request $request,
        PaginatorInterface $paginator,
        TranslatorInterface $translator,
        ReviewRepository $reviewRepository,
        Recipe $recipe
    ): Response {
        $keyword = '' == $request->query->get('keyword') ? 'all' : $request->query->get('keyword');

        if (!$recipe) {
            $this->addFlash('danger', $translator->trans('The recipe not be found'));

            $referer = $request->headers->get('referer');

            return $this->redirect($referer); // return to previous page
        }

        $reviews = $paginator->paginate(
            $reviewRepository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10,
            ['wrap-queries' => true]
        );

        return $this->render('recipe/reviews.html.twig', compact('recipe', 'reviews'));
    }
}
