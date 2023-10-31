<?php

namespace App\DataFixtures\Recipe;

use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\RecipeHasSource;
use App\Repository\Recipe\RecipeRepository;
use App\Repository\Recipe\SourceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppRecipeHasSourceFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public function __construct(
        protected readonly RecipeRepository $recipeRepository,
        protected readonly SourceRepository $sourceRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $recipes = $this->recipeRepository->findAll();
        $sources = $this->sourceRepository->findAll();

        foreach ($recipes as $recipe) {
            $recipeSources = $this->faker()->randomElements($sources, $this->faker()->numberBetween(0, 3));
            foreach ($recipeSources as $recipeSource) {
                $recipeHasSource = new RecipeHasSource();
                $recipeHasSource
                    ->setUrl($this->faker()->url())
                    ->setRecipe($recipe)
                    ->setSource($recipeSource)
                ;

                $manager->persist($recipeHasSource);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppRecipeFixtures::class,
            AppSourceFixtures::class,
        ];
    }
}
