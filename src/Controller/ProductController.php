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
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
    public function add(Request $request, FileUploader $fileUploader)
    {
        $user = $this->getUser();
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product)
            ->add("pictureName", FileType::class, ["data_class" => null ])
            ->add("save", SubmitType::class, ["label" => "Add Product"]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $file = $form->get('pictureName')->getData();
            $fileName = $fileUploader->upload($file);

            $product->setPictureName($fileName);
            $product->setUserId($user);
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
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findBy([],['releaseOn' => 'DESC']);
        return $this->render("product/all.html.twig", ["products" => $products, "user" => $user]);
    }

    /**
     * @Route("/show/{product}", name="product.show")
     */
    public function show(Product $product)
    {
        $user = $this->getUser();
//        dump($product->getPictureName());
        return $this->render("product/show.html.twig", ["product" => $product, "user" => $user]);
    }

    /**
     * @Route("/update/{product}", name="product.update")
     */
    public function update(Request $request, Product $product, AuthorizationCheckerInterface $authorizationChecker)
    {
        $user = $this->getUser();

        if ($product->getUserId() == $user or $authorizationChecker->isGranted("ROLE_ADMIN")) {

            $form = $this->createForm(ProductFormType::class, $product)
                ->add("save", SubmitType::class, ["label" => "Update Product"]);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->render("product/show.html.twig", ["product" => $product, "user" => $user]);

            }

            return $this->render("product/update.html.twig", ["form" => $form->createView()]);

        }

        $this->addFlash(
            'notice',
            'You cannot update Products you don\'t own !!'
        );
        return $this->render("product/show.html.twig", ["product" => $product, "user" => $user]);
    }

    /**
     * @Route("/updatePicture/{product}", name="product.updatePic")
     */
    public function updatePicture(Request $request, Product $product, FileUploader $fileUploader, Filesystem $filesystem, AuthorizationCheckerInterface $authorizationChecker, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        if ($product->getUserId() == $user or $authorizationChecker->isGranted("ROLE_ADMIN")) {

            $form = $this->createForm(ProductFormType::class, $product)
                ->add("pictureName", FileType::class, ["data_class" => null ])
                ->add("save", SubmitType::class, ["label" => "Update Picture"]);

            $oldFile = $this->getParameter('pictures_directory').'/'.$product->getPictureName();
//            dump($oldFile);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $filesystem->remove($oldFile);

                $file = $form->get('pictureName')->getData();
                $fileName = $fileUploader->upload($file);

                $product->setPictureName($fileName);
                $em->flush();
                return $this->redirectToRoute("product.show", ["product" => $product->getId(), "user" => $product->getUserId()->getId()]);

            }

            return $this->render("product/updatePic.html.twig", ["form" => $form->createView()]);

        }

        $this->addFlash(
            'notice',
            'You cannot update Products you don\'t own !!'
        );
        return $this->render("product/show.html.twig", ["product" => $product, "user" => $user]);
    }

    /**
     * @Route("/moderate/{product}", name="product.moderate")
     */
    public function moderation(Product $product, AuthorizationCheckerInterface $authorizationChecker)
    {
        if ($authorizationChecker->isGranted("ROLE_ADMIN")) {

            if (!$product->getAllowed()) {
                $product->setAllowed(true);
            } else {
                $product->setAllowed(false);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'notice',
                'Product have been moderated, thanks Mr Moderator :)'
            );
            return $this->redirectToRoute("product.all");
        }

        $this->addFlash(
            'notice',
            'You don\'t have moderation rights !'
        );
        return $this->redirectToRoute("product.all");
    }

    /**
     * @Route("/delete/{product}", name="product.delete")
     */
    public function delete(Product $product, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        if ($product->getUserId() == $user or $authorizationChecker->isGranted("ROLE_ADMIN")) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
            return $this->redirectToRoute("product.all");
        }

        $this->addFlash(
            'notice',
            'You cannot delete Products you don\'t own !!'
        );
        return $this->render("product/show.html.twig", ["product" => $product, "user" => $user]);
    }
}