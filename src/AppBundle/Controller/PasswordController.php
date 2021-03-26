<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Document\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as SecurityUserInterface;

class PasswordController extends Controller
{
    /**
     * @Route("/reset-password", name="reset_password_form")
     */
    public function VueResetPasswordAction()
    {
        return $this->render('AppBundle:Password:vue.html.twig');
    }

    /**
     * @Route("/reset-password/send-email", name="reset_password_send_email")
     */
    public function SendLinkResetAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();


        if($request->isMethod("post")){

            $identifiant = $_POST['identifiant'];

            $confirmationToken = sha1(uniqid(mt_rand()));

            $confirmationLink = 'http://'.$_SERVER['HTTP_HOST'].'/change-password/'.$confirmationToken;

            $regex_email = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i";

            if ( preg_match($regex_email, $identifiant) )
            {
                $email_user = $dm->getRepository('AppBundle:User')->findOneBy(array(
                    'email' => $identifiant
                ));

                if(!$email_user){
                    $request->getSession()->getFlashBag()->add('Utilisateur-Inexistant', 'Utilisateur Inexistant.');
                    return $this->redirectToRoute('reset_password_form');
                }

                $email_user->setConfirmationTokenEmail($confirmationToken);
                $dm->persist($email_user);

            }

            $transport = \Swift_SmtpTransport::newInstance()
                ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
                ->setHost($this->getParameter('mailer_host'))
                ->setPort(587)->setEncryption('tls');
    
            $mailer = \Swift_Mailer::newInstance($transport);
                       
            $message = \Swift_Message::newInstance()
                ->setSubject("SafeColis - Reinitialisation Mot de Passe")
                ->setFrom(array('service@safecolis.com' => 'Safecolis'))
                ->setTo(array( $identifiant => $identifiant))
                ->setBody(
                    $this->renderView(
                    'AppBundle:Mail:reset_password_mail.html.twig',array(
                        'email'=> $identifiant,
                        'confirmationLink'=> $confirmationLink
                    )
                    ),
                    'text/html'
                )
                ;
            ;
                    
            $result = $mailer->send($message);
            $dm->flush();

           return $this->redirectToRoute("reset_password_send_email_success", array('email' => $identifiant));
                
        
        }
        return $this->render('AppBundle:Password:vue.html.twig');
    }
    
    /**
     * @Route("/reset-password/{email}", name="reset_password_send_email_success")
     */
    public function SendLinkSuccessAction(Request $request, $email)
    {
        return $this->render('AppBundle:Password:reset_password_send_email_sucess.html.twig',
        array(
            'email' => $email ));
    }

    /**
     * @Route("/change-password/{token}", name="reset_password_change_password_form")
     */
    public function ChangePasswordFormAction(Request $request, $token)
    {
        $dm = $this->getDoctrine()->getManager();

        $user = $dm->getRepository('AppBundle:User')->findOneBy(array(
            'confirmationTokenEmail' => $token
        ));

        if(!$user){
            $request->getSession()->getFlashBag()->add('Lien-Reinit-Use', 'Lien de Réinitialisation déjà utilisé.');
            return $this->redirectToRoute('security_login_form');
        }

        return $this->render('AppBundle:Password:vue_change_password.html.twig', array(
            'token'=>$token,
            'email'=>$user->getEmail()));
    }

    /**
     * @Route("/change-password-success", name="reset_password_change_password")
     */
    public function ChangePasswordAction(Request $request)
    {
        if ($request->isMethod('POST')){

            $dm = $this->getDoctrine()->getManager();

            $user = $dm->getRepository('AppBundle:User')->findOneBy(array(
                'email' => $_POST['email']
            ));
            
            $password = $_POST['password'];

            $Repeatedpassword = $_POST['repeated'];

             $encoder = $this->container->get('security.password_encoder');
             $encoded = $encoder->encodePassword($user, $password); 
             $plainPassword = $encoded;

             if ($password == $Repeatedpassword)
             {
                $user->setPassword($plainPassword);
                $user->setConfirmationTokenEmail('');
                $dm->persist($user);
                $dm->flush();

                $request->getSession()->getFlashBag()->add('Reinitialisation-Complete', 'Votre Mot de passe a bien été changé.');
                return $this->redirectToRoute('security_login_form');
             }
            $request->getSession()->getFlashBag()->add('mot_passe_different', 'Mot de passe non identique.');
            return $this->redirectToRoute('change_password');
                       
        }
    }

}