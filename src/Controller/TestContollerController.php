<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestContollerController extends Controller
{
    /**
     * @Route("/test/contoller/{nombre}", name="test_contoller")
     * @Template("main/test.html.twig")
     */
    public function index($nombre=12)
    {
        return  ["nombre" => $nombre];
    }
}
