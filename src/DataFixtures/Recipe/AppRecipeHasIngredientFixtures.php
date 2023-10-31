<?php

namespace App\DataFixtures\Recipe;

use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\Ingredient;
use App\Entity\Recipe\IngredientGroup;
use App\Entity\Recipe\RecipeHasIngredient;
use App\Entity\Recipe\Unit;
use App\Repository\Recipe\IngredientRepository;
use App\Repository\Recipe\RecipeRepository;
use App\Repository\Recipe\UnitRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppRecipeHasIngredientFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public function __construct(
        protected readonly UnitRepository $unitRepository,
        protected readonly RecipeRepository $recipeRepository,
        protected readonly IngredientRepository $ingredientRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $units = $this->unitRepository->findAll();
        $ingredients = $this->ingredientRepository->findAll();
        $recipes = $this->recipeRepository->findAll();

        $groups = [];
        for ($i = 0; $i < 20; ++$i) {
            $group = new IngredientGroup();
            /** @var string $name */
            $name = $this->faker()->words(2, true);
            $group
                ->setName($name)
                ->setPriority(1)
            ;
            $manager->persist($group);
            $groups[] = $group;
        }

        foreach ($recipes as $recipe) {
            $recipeGroups = [];
            if ($this->faker()->boolean(25)) {
                $recipeGroups = $this->faker()->randomElements($groups, $this->faker()->numberBetween(2, 3));
            }

            for ($i = 0; $i < $this->faker()->numberBetween(2, 8); ++$i) {
                $rhi = new RecipeHasIngredient();

                /** @var Unit $unit */
                $unit = $this->faker()->randomElement($units);

                /** @var Ingredient $ingredient */
                $ingredient = $this->faker()->randomElement($ingredients);
                $rhi
                    ->setUnit($unit)
                    ->setIngredient($ingredient)
                    ->setRecipe($recipe)
                    ->setOptional($this->faker()->boolean(10))
                    ->setQuantity($this->faker()->randomFloat(1, 0, 10))
                ;

                if (count($recipeGroups) > 0) {
                    /** @var IngredientGroup $group */
                    $group = $this->faker()->randomElement($recipeGroups);
                    $rhi->setIngredientGroup($group);
                }

                $manager->persist($rhi);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppIngredientFixtures::class,
            AppRecipeFixtures::class,
            AppUnitFixtures::class,
        ];
    }
}
