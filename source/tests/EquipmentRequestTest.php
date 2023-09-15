<?php

namespace App\Tests;

use App\Entity\Equipment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class EquipmentRequestTest extends WebTestCase
{   
    protected $client = null;
    protected EntityManagerInterface $entityManager;
    
    
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }
    
    public function testCreateEquipment():void
    {

        $this->client->request(
            'POST', 'api/equipment',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'name' => 'test',
                'category' => 'mobile',
                'number' => '133444',
            ])
        );


        $response = $this->client->getResponse();

      
        $arr = (array) json_decode($response->getContent());
              
        $this->assertResponseIsSuccessful();
        
        $this->assertResponseHeaderSame(
            'Content-Type', 'application/json; charset=utf-8'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->assertArrayHasKey('id', $arr);
        $this->assertArrayHasKey('name', $arr);
        $this->assertArrayHasKey('category', $arr);
        $this->assertArrayHasKey('description', $arr);
        $this->assertArrayHasKey('createdAt', $arr);

    }

    public function testGetAllEquipments():void
    {     
               
        $this->client->request(
            'GET',
            '/api/equipment'
        );

        $response = $this->client->getResponse();
       
        $this->assertResponseIsSuccessful();
        
        $this->assertResponseHeaderSame(
            'Content-Type', 'application/json; charset=utf-8'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $equipments = json_decode($response->getContent());

        $this->assertIsArray($equipments);
    }

    public function testUpdateEquipment():void
    {
               
        $record = $this->entityManager->getRepository(Equipment::class)->findOneBy(['name' => 'test']);
        
        $recordId = $record->getId();

        $this->client->request(
            'PUT', 'api/equipment/'.$recordId,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'name' => 'test updated',
                'category' => 'mobile',
                'number' => '133444',
                'description' => 'this is the description',
            ])
        );

        $response = $this->client->getResponse();

        $obj = json_decode($response->getContent());
 
        $this->assertResponseIsSuccessful();
        
        $this->assertResponseHeaderSame(
            'Content-Type', 'application/json; charset=utf-8'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertIsObject($obj);

        $this->assertObjectHasProperty('updatedAt', $obj, 'has updatedAt property');
    }

    public function testDeleteEquipment():void
    {

        $record = $this->entityManager->getRepository(Equipment::class)->findOneBy(['name' => 'test updated']);
        
        $recordId = $record->getId();       

        $this->client->request(
            'DELETE', 
            'api/equipment/'.$recordId
        );

        $this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

    }

}