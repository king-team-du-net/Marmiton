<?php

namespace App\DataFixtures\Recipe;

use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\Source;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppSourceFixtures extends Fixture
{
    use FakerTrait;

    public function load(ObjectManager $manager): void
    {
        // Create 75 Sources
        for ($i = 0; $i < 75; ++$i) {
            $source = new Source();
            $source
                ->setName($this->faker()->name())
                ->setContent($this->faker()->text(250))
                ->setUrl($this->faker()->url())
            ;

            $manager->persist($source);
        }

        $manager->flush();
    }
}
