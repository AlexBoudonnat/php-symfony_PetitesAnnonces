<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 26/07/2018
 * Time: 15:27
 */

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class UserApiController
 * @package App\Controller
 * @Route("/api")
 */
class UserApiController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/users")
     */
    public function getAllUsers(EntityManagerInterface $em)
    {
        $users = $em->getRepository(User::class)->findAll();
//        dump($users);exit;
        return new JsonResponse($users);
    }

    /**
     * @Method("GET")
     * @Route("/user/{user}")
     */
    public function getOneUser(EntityManagerInterface $em, User $user, SerializerInterface $serializer)
    {
        return new JsonResponse($user);
//        return $this->json($user);
    }
}