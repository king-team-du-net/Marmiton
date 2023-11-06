<?php

namespace App\Controller;

use App\Entity\Setting\Setting;
use App\Repository\Blog\ArticleRepository;
use App\Repository\Recipe\RecipeRepository;
use App\Repository\Setting\HomepageHeroSettingRepository;
use App\Repository\TestimonialRepository;
use App\Service\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        HomepageHeroSettingRepository $homepageHeroSettingRepository,
        SettingService $settingService,
        ArticleRepository $articleRepository,
        TestimonialRepository $testimonialRepository,
        RecipeRepository $recipeRepository,
    ): Response {
        /** @var int $limitArticles */
        $limitArticles = $settingService->getValue(Setting::HOMEPAGE_ARTICLES_NUMBER);

        /** @var int $limitTestimonials */
        $limitTestimonials = $settingService->getValue(Setting::HOMEPAGE_TESTIMONIALS_NUMBER);

        /** @var int $limitRecipes */
        $limitRecipes = $settingService->getValue(Setting::HOMEPAGE_RECIPES_NUMBER);

        return $this->render('home/index.html.twig', [
            'herosettings' => $homepageHeroSettingRepository->find(1),
            'lastArticles' => $articleRepository->findLastRecent($limitArticles),
            'lastTestimonials' => $testimonialRepository->findLastRecent($limitTestimonials),
            'lastRecipes' => $recipeRepository->findLastRecent($limitRecipes),
        ]);
    }
}
