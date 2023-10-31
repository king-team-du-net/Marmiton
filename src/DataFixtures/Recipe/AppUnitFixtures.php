<?php

namespace App\DataFixtures\Recipe;

use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppUnitFixtures extends Fixture
{
    use FakerTrait;

    public function load(ObjectManager $manager): void
    {
        $units = [
            'gramme' => 'grammes',
            'milligramme' => 'milligrammes',
            'kg' => 'kg',
            'pincée' => 'pincées',
            'poignée' => 'poignées',
            'tasse' => 'tasses',
            'litre' => 'litres',
            'centilitre' => 'centilitres',
            'millilitre' => 'millilitres',
            'cuillère à soupe' => 'cuillères à soupe',
            'cuillère à café' => 'cuillères à café',
        ];

        foreach ($units as $singular => $plural) {
            $unit = new Unit();
            $unit
                ->setSingular($singular)
                ->setPlural($plural)
            ;

            $manager->persist($unit);
        }

        $manager->flush();
    }
}
