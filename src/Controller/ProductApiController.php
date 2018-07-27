<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 27/07/2018
 * Time: 11:51
 */

namespace App\Controller;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductApiController
 * @package App\Controller
 * @Route("/api")
 */
class ProductApiController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/products")
     */
    public function getAllUsers(EntityManagerInterface $em)
    {
        $products = $em->getRepository(Product::class)->findAll();
//        dump($products);exit;
        return new JsonResponse($products);
    }

    /**
     * @Method("GET")
     * @Route("/product/{product}")
     */
    public function getOneUser(EntityManagerInterface $em, Product $product)
    {
        return new JsonResponse($product);
    }
}