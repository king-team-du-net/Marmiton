<?php

namespace App\Controller\Dashboard\Shared;

use App\Entity\Book;
use App\Entity\Review;
use App\Entity\HasRoles;
use App\Form\ReviewType;
use App\Controller\Controller;
use App\Service\SettingService;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Recipe\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[IsGranted(HasRoles::DEFAULT)]
class ReviewController extends Controller
{
    public function __construct(
        private readonly RecipeRepository $recipeRepository,
        private readonly SettingService $settingService
    ) {
    }

    #[Route(path: '/%website_dashboard_path%/user/my-reviews', name: 'dashboard_user_reviews_list', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/admin/manage-reviews', name: 'dashboard_admin_reviews_list', methods: [Request::METHOD_GET])]
    public function list(Request $request, AuthorizationCheckerInterface $authChecker, PaginatorInterface $paginator): Response
    {
        $keyword = ($request->query->get('keyword')) == "" ? "all" : $request->query->get('keyword');
        $recipe = ($request->query->get('recipe')) == "" ? "all" : $request->query->get('recipe');
        $visible = ($request->query->get('visible')) == "" ? "all" : $request->query->get('visible');
        $rating = ($request->query->get('rating')) == "" ? "all" : $request->query->get('rating');
        $slug = ($request->query->get('slug')) == "" ? "all" : $request->query->get('slug');

        $user = "all";
        if ($authChecker->isGranted(HasRoles::DEFAULT)) {
            $user = $this->getUser()->getSlug();
        }

        $reviews = $paginator->paginate(
            $this->settingService->getReviews(["user" => $user, "keyword" => $keyword, "recipe" => $recipe, "slug" => $slug, "visible" => $visible, "rating" => $rating])->getQuery(), 
            $request->query->getInt('page', 1), 
            10, 
            ['wrap-queries' => true]
        );

        return $this->render('dashboard/shared/review/list.html.twig', compact('reviews'));
    }

    #[Route(path: '/%website_dashboard_path%/user/my-reviews/{slug}/create', name: 'dashboard_user_reviews_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(
        Request $request, 
        EntityManagerInterface $em, 
        TranslatorInterface $translator, 
        UrlGeneratorInterface $url
    ): Response {
        $recipe = $this->recipeRepository->findOneBy([], ['id' => 'desc']);
        if (!$recipe) {
            $this->addFlash('danger', $translator->trans('The recipe not be found'));

            return $this->redirectToRoute("recipes");
        }

        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review)->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $review->setUser($this->getUser());
                $review->setRecipe($recipe);

                $em->persist($review);
                $em->flush();

                $this->addFlash('success', $translator->trans('Your review has been successfully saved'));

                return $this->redirect($url->generate('recipe', ['id' => $recipe->getId()]) . '#reviews');
            } else {
                $this->addFlash('danger', $translator->trans('The form contains invalid data'));
            }
        }

        return $this->render('dashboard/shared/review/create.html.twig', compact('form', 'review', 'recipe'));
    }
}