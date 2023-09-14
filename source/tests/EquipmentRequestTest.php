<?php

namespace App\Tests;

use App\Entity\Equipment;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EquipmentRequestTest extends WebTestCase
{


    public function testGetAllEquipments():void
    {
        $client = static::createClient();

        $client->request('GET', '/api/equipment');

        // $response = $client->getResponse();
       
        $this->assertResponseIsSuccessful();
        
        $this->assertResponseHeaderSame(
            'Content-Type', 'application/json; charset=utf-8'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateEquipment(){
        $client = static::createClient();

        $client->request(
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
        
        $this->assertResponseIsSuccessful();
        
        $this->assertResponseHeaderSame(
            'Content-Type', 'application/json; charset=utf-8'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        
    }

    public function testUpdateEquipment(){
        $client = self::createClient();
        
        $em = self::getContainer()->get('doctrine')->getManager();

        $record = $em->getRepository(Equipment::class)->findOneBy(['name' => 'test']);
        
        $recordId = $record->getId();
       

        $client->request(
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
            ])
        );
    
        
        $this->assertResponseIsSuccessful();
        
        $this->assertResponseHeaderSame(
            'Content-Type', 'application/json; charset=utf-8'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    public function testDeleteEquipment(){
        $client = self::createClient();
        
        $em = self::getContainer()->get('doctrine')->getManager();

        $record = $em->getRepository(Equipment::class)->findOneBy(['name' => 'test updated']);
        
        $recordId = $record->getId();
       

        $client->request(
            'DELETE', 'api/equipment/'.$recordId
        );

        $this->assertResponseIsSuccessful();

    }

}