<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $equipment = new Equipment;
        $equipment->setName("Huawei Nova 6");
        $equipment->setCategory("téléphone");
        $equipment->setNumber("000001");
        $equipment->setDescription("Android os 10, quad core, 4Go RAM, 64Go storage");
        $manager->persist($equipment);

        $equipment = new Equipment;
        $equipment->setName("Acer aspire 575");
        $equipment->setCategory("ordinateur");
        $equipment->setNumber("000002");
        $equipment->setDescription("Laptop core i5, 8Go RAM, 256 SSD");
        $manager->persist($equipment);

       
        $manager->flush();
    }
}
