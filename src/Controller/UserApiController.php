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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserApiController extends Controller
{
    /**
     * @Route("/GET/users")
     */
    public function getAllUsers(EntityManagerInterface $em)
    {
        $users = $em->getRepository(User::class)->createQueryBuilder('u')->getQuery()->getArrayResult();
        dump($users);
        return new JsonResponse($users);
    }

//    /**
//     * @Route("/GET/user/{user}")
//     */
//    public function getUser(EntityManagerInterface $em, User $user)
//    {
//        dump($user); exit;
//        $thisUser = $em->getRepository(User::class)->createQueryBuilder('u')->Where('u.id = '.$user->getId())->getQuery()->getArrayResult();
//        return new JsonResponse($thisUser);
//    }
}