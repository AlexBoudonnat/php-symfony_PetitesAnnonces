<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 17/07/2018
 * Time: 13:59
 */

namespace App\Controller;

use App\Entity\Product;
use App\Service\MsgGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; //add this line to add usage of Route class.

class MainController extends Controller
{

    /**
     * @Route("/", name="app_home")
     * @Template("main/home.html.twig")
     */
    public function home(MsgGenerator $msgGenerator)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findBy(['userId' => $user],['releaseOn' => 'DESC']);

        $message = $msgGenerator->getHappyMessage();

        return ["products" => $products, "project_name" => $message];
    }
}