<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as SecurityUserInterface;


class RegisterController extends Controller
{

    /**
     * @Route("/register", name="register_form")
     */
    public function registerAction(Request $request)
    {
 
        $dm = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST'))
        {
            //on recupere tous champs du formulaire
            //fonction de génération du mot de passe
            $min = 100000;
            $max = 999999;
            $password_generate = rand ($min, $max );
            $email = $_POST['email'];
            $terms = isset($_POST['terms']) ? $_POST['terms'] : NULL;
        
            if(!$email)
            {
                $request->getSession()->getFlashBag()->add('email_vide', 'Champs Email vide');
                return $this->redirectToRoute('security_register_form');
            }

            if($terms == NULL )
            {
                $request->getSession()->getFlashBag()->add('terms_vide', 'Veuillez Accpeter les conditions d\'utilisation');
                return $this->redirectToRoute('register_form');
            }
            
            $ExistantEmail = $dm->getRepository('AppBundle:User')->findBy(
                array(
                    'email' =>  $email
                )
            );

            if($ExistantEmail)
            {
                $request->getSession()->getFlashBag()->add('email_existant', 'Adresse Email déjà existante ');
                return $this->redirectToRoute('register_form');
            }
    
            $regex_email = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i";


            if ( !preg_match($regex_email, $email) )
            {
                $request->getSession()->getFlashBag()->add('Email_non_valide', 'Email non valide ');
                return $this->redirectToRoute('register_form');
            }
                     
            $confirmationTokenEmail = sha1(uniqid(mt_rand()));
            $salt = sha1(uniqid(mt_rand()));
            $confirmationLink = 'http://'.$_SERVER['HTTP_HOST'].'/login/'.$confirmationTokenEmail;
                
            $user = new User();
            $user->setUsername('');
            $user->setEmail($email);
            $user->setPlainPassword($password_generate);
            $user->setIsActive(false);
            
            $user->setBibliographie('');
            $user->setSexe('Undefined');
            $user->setTermcondition($terms);
            $user->setDateInscription(new \Datetime());
            $user->setNom('');
            $user->setPrenom('');
            $user->setSalt('');
            $user->setLocalisation("Undefined");
            $user->setConfirmationTokenEmail($confirmationTokenEmail);
            $user->setRoles(array('ROLE_USER'));
    
            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $user->encodePassword($encoder);

            $transport = \Swift_SmtpTransport::newInstance()
            ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
            ->setHost($this->getParameter('mailer_host'))
            ->setPort(587)->setEncryption('tls');

            $mailer = \Swift_Mailer::newInstance($transport);
                        
            $message = \Swift_Message::newInstance()
                ->setSubject("SafeColis - Inscription")
                ->setFrom(array('service@safecolis.com' => 'Safecolis'))
                    ->setTo(array( $email => $email))
                    ->setBody(
                        $this->renderView(
                        'AppBundle:Mail:register_send_email.html.twig',array(
                            'email'=> $email,
                            'confirmationLink'=> $confirmationLink,
                            'password' => $password_generate
                        )
                        ),
                        'text/html'
                    )
                    ;
                ;
                       
               
                $result = $mailer->send($message);
                $dm->persist($user);
                $dm->flush();
                
                return $this->redirectToRoute('register_wait', array('email'=> $email));
                          
        }
 
        return $this->render('AppBundle:Register:registerform.html.twig');
    }

    /**
     * @Route("/register/checkEmail/{email}", name="register_wait")
     */
    public function checkEmailAction(Request $request, $email)
    {
        return $this->render('AppBundle:Register:register_wait.html.twig',
        array(
            'email' => $email ));
    }

     /**
     * @Route("/login/{token}", name="register_active_compte")
     */
    public function ActiveAction($token, Request $request){

        $dm = $this->getDoctrine()->getManager();

        $user = $dm->getRepository('AppBundle:User')->findOneBy(array(
            'confirmationTokenEmail' => $token
        ));

        if(!$user){
            $request->getSession()->getFlashBag()->add('Lien-inscrip-Use', 'Lien d\'inscription déjà utilisé.');
            return $this->redirectToRoute('security_login_form');
        }   

        $user->setIsActive(true);
        $user->setConfirmationTokenEmail(false);
        $dm->persist($user);
        $dm->flush();
        
        $request->getSession()->getFlashBag()->add('compte-actif', 'Inscription terminé!!! votre compte est maintenant actif.');
        return $this->redirectToRoute('security_login_form');

    }
}
