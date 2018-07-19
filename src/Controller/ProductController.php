<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 17/07/2018
 * Time: 15:35
 */

namespace App\Controller;


use App\Entity\Product;
use App\Form\ProductFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/add", name="product.add")
     */
    public function add(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product)
            ->add("save", SubmitType::class, ["label" => "Add Product"]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute("product.all");

        }

        return $this->render("product/add.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/all", name="product.all")
     */
    public function all()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();
        return $this->render("product/all.html.twig", ["products" => $products]);
    }

    /**
     * @Route("/show/{product}", name="product.show")
     */
    public function show(Product $product)
    {
        return $this->render("product/show.html.twig", ["product" => $product]);
    }

    /**
     * @Route("/update/{product}", name="product.update")
     */
    public function update(Request $request, Product $product)
    {
        $form = $this->createForm(ProductFormType::class, $product)
            ->add("save", SubmitType::class, ["label" => "Update Product"]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("product.all");

        }

        return $this->render("product/update.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/delete/{product}", name="product.delete")
     */
    public function delete(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute("product.all");
    }
}