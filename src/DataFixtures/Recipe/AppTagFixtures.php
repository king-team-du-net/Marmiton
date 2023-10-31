<?php

namespace App\DataFixtures\Recipe;

use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppTagFixtures extends Fixture
{
    use FakerTrait;

    public function load(ObjectManager $manager): void
    {
        // Create 200 Tags
        $tags = [];
        for ($i = 0; $i < 200; ++$i) {
            /** @var Tag $parent */
            $parent = $this->faker()->optional(weight: 0.125)->randomElement($tags);

            $tag = new Tag();
            $tag
                ->setName($this->faker()->name())
                ->setContent($this->faker()->text(250))
                ->setMenu($this->faker()->boolean(30))
                ->setParent($parent)
            ;

            $manager->persist($tag);

            $tags[] = $tag;
        }

        $manager->flush();
    }
}
