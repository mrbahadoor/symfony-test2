<?php

namespace App\Tests;

use App\Entity\Equipment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;


class EquipmentRequestTest extends ApiTestCase
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
       
        $response = $this->client->request(
            'POST',
            '/api/equipment',
            [
                'headers' => ['Accept' => 'application/json'],
                'json' => [
                    'name' => 'test',
                            'category' => 'mobile',
                            'number' => '133444',
                ],
            ]
        );

      
        $arr = $response->toArray();
              
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
            '/api/equipment',
            [
                'headers' => ['Accept' => 'application/json'],
            ]
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
       
        $response = $this->client->request(
            'PUT', 
            '/api/equipment/'.$recordId,
            [
                'headers' => ['Accept' => 'application/json'],
                'json' => [
                    'name' => 'test updated',
                    'category' => 'mobile',
                    'number' => '133444',
                    'description' => 'this is the description',
                ],
            ]
        );

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
            'api/equipment/'.$recordId,
            [
                'headers' => ['Accept' => 'application/json'],
            ]
        );

        $this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

    }

}