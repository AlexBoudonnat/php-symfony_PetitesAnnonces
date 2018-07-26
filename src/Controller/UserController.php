<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 19/07/2018
 * Time: 09:51
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\UserChangeType;
use App\Form\UserType;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/register", name="user.register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, Mailer $mailer)
    {
        // 1) Build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user)
            ->add("save", SubmitType::class, ["label" => "Create profile"]);

        // 2) Handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) Check for errors
            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return $this->render('registration/register.html.twig', ['errors' => $errors]);
            }

            // 5) Save the User !
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //6) Send a welcome email
            $mailer->sendWelcomeMail($user);

            //7) flash msg of profile creation
            $this->addFlash(
                'notice',
                'Hello '.$user->getUsername().'. You Profile as been created ! You can login now.'
            );

            return $this->redirectToRoute("app_home");
        }

        return $this->render("registration/register.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/user/profile", name="user.profile")
     */
    public function showProfile()
    {
        $user = $this->getUser();
        $currentUser = $user;
        return $this->render("user/profile.html.twig", ["user" => $user, "currentUser" => $currentUser]);
    }

    /**
     * @Route("/user/show/{user}", name="user.show")
     */
    public function show(User $user)
    {
        $currentUser = $this->getUser();
        return $this->render("user/profile.html.twig", ["user" => $user, "currentUser" => $currentUser]);
    }

    /**
     * @Route("/user/update", name="user.update")
     */
    public function update(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserChangeType::class, $user)
            ->add("save", SubmitType::class, ["label" => "Update your profile"]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
//            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash(
                'notice',
                'Your changes have been saved!'
            );
            return $this->redirectToRoute("user.profile");

        }
        return $this->render("user/update.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/user/changePwd", name="user.changePwd")
     */
    public function changePwd(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user)
            ->add("save", SubmitType::class, ["label" => "Update your profile"]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash(
                'notice',
                'Your new password is saved!'
            );
            return $this->redirectToRoute("user.profile");

        }

        return $this->render("user/change.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/user/delete", name="user.delete")
     */
    public function delete(SessionInterface $session)
    {
        $user = $this->getUser();
        $this->get('security.token_storage')->setToken(null);
        $session->invalidate();
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("app_home");
    }
}