<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/07/2018
 * Time: 10:15
 */

namespace App\Service;


use App\Entity\User;

/**
 * @property \Twig_Environment templating
 */
class Mailer
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
    }
    public function sendWelcomeMail(User $user)
    {
        try {
            $message = (new \Swift_Message('Welcome Email'))
                ->setFrom('alexcampusnum74@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->templating->render(
                    // templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        array('user' => $user)
                    ),
                    'text/html'
                );
        } catch (\Twig_Error_Loader | \Twig_Error_Runtime | \Twig_Error_Syntax $e) {

        }

        $this->mailer->send($message);
    }
}