<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 17/07/2018
 * Time: 13:34
 */

namespace App\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; //add this line to add usage of Route class.

class HelloWorldController extends Controller
{

    /**
     * @Route("/hello", name="app_hello") //add this comment to annotations
     * @Template("main/helloWorld.html.twig")
     */
    public function hello()
    {

        return ["hello_name" => "toi"];
    }

}