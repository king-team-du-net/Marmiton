<?php

namespace App\DataFixtures\Recipe;

use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppIngredientFixtures extends Fixture
{
    use FakerTrait;

    public function load(ObjectManager $manager): void
    {
        // Create 120 Ingredients
        for ($i = 0; $i < 120; ++$i) {
            /** @var string $name */
            $name = $this->faker()->words(4, true);

            $ingredient = new Ingredient();
            $ingredient
                ->setName($name)
                ->setContent($this->faker()->realText(125))
                ->setDairyFree($this->faker()->boolean())
                ->setGlutenFree($this->faker()->boolean())
                ->setVegan($this->faker()->boolean())
                ->setVegetarian($this->faker()->boolean())
            ;

            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
