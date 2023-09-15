<?php

namespace App\Controller;

use App\Entity\Equipment;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/v1/equipment')]
class EquipmentController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }    
    
    
    #[Route('/', name: 'equipment_list', methods: ['GET'])]
    public function listEquipments(Request $request)
    {

        $entityManager = $this->doctrine->getManager();
        $equipmentRepository = $entityManager->getRepository(Equipment::class);
        $filters = [];

        $fields = ['id', 'name', 'category', 'number', 'description'];

        foreach($fields as $field){
            if($request->query->has($field)){
                $filters[$field] = $request->query->get($field);
            }
        }

        $equipments = $equipmentRepository->findByFilters($filters);

        return $this->json($equipments, Response::HTTP_OK);

    }

    #[Route('/{id}', name: 'equipment_by_id', requirements: ['id' => '\d+'], methods: ['GET'])]    
    public function equipmentById($id)
    {
        $equipment = $this->doctrine->getRepository(Equipment::class)->find($id);
        if($equipment){
            return $this->json($equipment, Response::HTTP_OK);
        }else{
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('', name: 'equipment_add', methods: ['POST'])]
    public function addEquipment(Request $request, SerializerInterface $serializer)
    {
        $equipment = $serializer->deserialize($request->getContent(), Equipment::class, 'json');
        $em = $this->doctrine->getManager();
        $em->persist($equipment);
        $em->flush();

        return $this->json($equipment, Response::HTTP_CREATED);
    }
    

    #[Route('/{id}', name: 'equipment_update', requirements: ['id' => '\d+'], methods: ['PUT'])]    
    public function updateEquipment($id, Request $request)
    {
        $equipment = $this->doctrine->getRepository(Equipment::class)->find($id);
        
        $parameters = json_decode($request->getContent(), true);

        $allowedFieds = ['name', 'category', 'number', 'description'];

        $countFieldsToUpdate = 0;

        foreach($parameters as $key => $value){
            if(isset($parameters[$key]) && in_array($key, $allowedFieds)){
                $methodName = 'set'.ucfirst($key);

                // Dynamic setters
                $equipment->{$methodName}($value);

                $countFieldsToUpdate += 1;
            }
        }

        if($countFieldsToUpdate > 0){

            $em = $this->doctrine->getManager();
            $em->persist($equipment);
            $em->flush();

            return $this->json($equipment, Response::HTTP_CREATED);
        }else{
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        
    }

    #[Route('/{id}', name: 'equipment_delete', methods: ['DELETE'])]
    public function deleteEquipment(Equipment $equipment)
    {
        $em = $this->doctrine->getManager();
        $em->remove($equipment);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    
}