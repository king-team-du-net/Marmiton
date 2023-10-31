<?php

namespace App\DataFixtures\Recipe;

use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\Recipe;
use App\Entity\Recipe\Step;
use App\Repository\Recipe\RecipeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppStepFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public function __construct(protected readonly RecipeRepository $recipeRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $recipes = $this->recipeRepository->findAll();

        // Create 250 Recipes
        for ($i = 0; $i < 250; ++$i) {
            /** @var Recipe $recipe */
            $recipe = $this->faker()->randomElement($recipes);

            $step = new Step();
            $step
                ->setContent($this->faker()->realText())
                ->setPriority($this->faker()->randomDigitNotZero())
                ->setRecipe($recipe)
            ;

            $manager->persist($step);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppRecipeFixtures::class,
        ];
    }
}
