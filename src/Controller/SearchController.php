<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 25/07/2018
 * Time: 11:58
 */

namespace App\Controller;


use App\Entity\Product;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="product.search")
     */
    public function search(Request $request)
    {
        $user = $this->getUser();
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add("search", TextType::class)
            ->add("localisation", ChoiceType::class, array(
                'choices' => array(
                    'Location' => null,
                    'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
                    'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
                    'Bretagne' => 'Bretagne',
                    'Centre-Val de Loire' => 'Centre-Val de Loire',
                    'Corse' => 'Corse',
                    'Grand Est' => 'Grand Est',
                    'Hauts-de-France' => 'Hauts-de-France',
                    'Île-de-France' => 'Île-de-France',
                    'Normandie' => 'Normandie',
                    'Nouvelle-Aquitaine' => 'Nouvelle-Aquitaine',
                    'Occitanie' => 'Occitanie',
                    'Pays de la Loire' => 'Pays de la Loire',
                    'Provence-Alpes-Côte d\'Azur' => 'Provence-Alpes-Côte d\'Azur',
                )
            ))
            ->add("category", ChoiceType::class, array(
                'choices' => array(
                    'Category' => null,
                    'Emploi' => 'emploi',
                    'Véhicules' => 'vehicules',
                    'Immobilier' => 'immobilier',
                    'Vacances' => 'vacances',
                    'Multimedia' => 'multimedia',
                    'Materiel Professionnel' => 'materielPro',
                    'Services' => 'services',
                    'Maison' => 'maison',
                    'Autres' => 'autres',
                )
            ))
            ->add("save", SubmitType::class, ["label" => "Search"])
            ->getForm();
//        $form = $this->createForm(SearchType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            dump($product);

            if ($product->getCategory() == null && $product->getLocalisation() == null) {
                $products = $em->getRepository(Product::class)->createQueryBuilder('p')
                    ->andWhere('p.name LIKE :name')
                    ->setParameter('name', '%'.$product->getSearch().'%')
                    ->getQuery()
                    ->execute();

            } elseif ($product->getCategory() == null) {
                $products = $em->getRepository(Product::class)->createQueryBuilder('p')
                    ->andWhere('p.name LIKE :name')
                    ->setParameter('name', '%'.$product->getSearch().'%')
                    ->andWhere('p.localisation = :localisation')
                    ->setParameter('localisation', $product->getLocalisation())
                    ->getQuery()
                    ->execute();

            } elseif ($product->getLocalisation() == null) {
                $products = $em->getRepository(Product::class)->createQueryBuilder('p')
                    ->where('p.category = :category')
                    ->setParameter('category', $product->getCategory())
                    ->andWhere('p.name LIKE :name OR p.description LIKE :name')
                    ->setParameter('name', '%'.$product->getSearch().'%')
                    ->getQuery()
                    ->execute();

            } else {
                $products = $em->getRepository(Product::class)->createQueryBuilder('p')
                    ->where('p.category = :category')
                    ->setParameter('category', $product->getCategory())
                    ->andWhere('p.name LIKE :name')
                    ->setParameter('name', '%'.$product->getSearch().'%')
                    ->andWhere('p.localisation = :localisation')
                    ->setParameter('localisation', $product->getLocalisation())
                    ->getQuery()
                    ->execute();
            }




            return $this->render("search/result.html.twig", ["products" => $products, "user" => $user]);

        }
        return $this->render("search/newSearch.html.twig", ["form" => $form->createView()]);
    }
}