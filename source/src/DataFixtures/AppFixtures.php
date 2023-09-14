<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create();
        
        for($i = 0; $i < 100; $i++){
            $equipment = new Equipment;
            $equipment->setName($faker->catchPhrase());
            $equipment->setCategory($faker->safeColorName());
            $equipment->setNumber(sprintf('%06d', $i+1));
            $equipment->setDescription($faker->sentence());
            $manager->persist($equipment);
        }

        $manager->flush();
    }
}
