<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/equipment")
 */
class EquipmentController extends AbstractController
{
    /**
     * @Route("/list")
     */
    public function list()
    {
        return new Response("test");
    }

}