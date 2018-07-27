<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 25/07/2018
 * Time: 11:58
 */

namespace App\Controller;


use App\Entity\Search;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="product.search")
     */
    public function search(Request $request, EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $user = $this->getUser();
        $search = new Search();

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $products = $productRepository->getSearchByKeywordsAndLocalisationAndCategory($search, $em);

            return $this->render("search/result.html.twig", ["products" => $products, "user" => $user]);

        }
        return $this->render("search/newSearch.html.twig", ["form" => $form->createView()]);
    }
}