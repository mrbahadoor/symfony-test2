<?php

namespace App\Tests\Repository;

use App\Entity\Equipment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class EquipmentRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }


    public function testAddOneEquipment():void
    {
        
        $descText = 'description '.strtotime('now');
        // Setup
        $equipment = new Equipment();
        $equipment->setName('test');
        $equipment->setCategory('test category');
        $equipment->setNumber('123456');
        $equipment->setDescription($descText);

        $this->entityManager->persist($equipment);
        $this->entityManager->flush();

        // Do something
        $repository = $this->entityManager->getRepository(Equipment::class);
        $record = $repository->findOneBY(['description' => $descText]);

        // Make assertions
        $this->assertEquals($descText, $record->getDescription());
        $this->assertEquals('test', $record->getName());
        $this->assertEquals('test category', $record->getCategory());
        $this->assertEquals('123456', $record->getNumber());
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        // doing this is recommended to avoid memory leaks
      
        $this->entityManager->close();
        $this->entityManager = null;
    }

}