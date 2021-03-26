<?php

namespace SafeColis\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use SafeColis\HomeBundle\Entity\Policy;
use SafeColis\HomeBundle\Entity\Userterms;

class HomeController extends Controller
{
    public function indexAction()
    {
        $helper = $this->get('security.authentication_utils');

        $namepage = "Apps";
        return $this->render('SafeColisHomeBundle:Home:index.html.twig', array(
            'namepage'=>$namepage,
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError()
        ));
    }

    public function politiqueAction(Request $request)
    {
        $namepage = "Politique de Confidentialité";

        $policy = new Policy();

        $em = $this->getDoctrine()->getManager(); 

        $policypage = $em->getRepository('SafeColisHomeBundle:Policy')->findAll(); 

        if(empty($policypage))
        {
            $request->getSession()->getFlashBag()->add('policy_emplty', 'Politique de confidentialité vide.');
            $currrentPolicy = ' ';

            if($request->isMethod('POST'))
            {
                $policy->setDateEdit($_POST['date_edit']);
                $policy->setContent($_POST['content']);
                $em->persist($policy);
                $em->flush();
                $request->getSession()->getFlashBag()->add('policy_edit', 'Politique de confidentialité crée avec success.');
                return $this->redirectToRoute('safe_colis_home_politique');

            }

        }
        else{
            foreach($policypage as $key)
            {

            }
            $currrentPolicy = $key;

            if($request->isMethod('POST'))
            {
                $key->setDateEdit($_POST['date_edit']);
                $key->setContent($_POST['content']);
                $em->persist($policypage);
                $em->flush();
                $request->getSession()->getFlashBag()->add('policy_edit', 'Politique de confidentialité crée avec success.');
                return $this->redirectToRoute('safe_colis_home_politique');

            }

        }
    
        if($request->isMethod('POST'))
        {

        }

        return $this->render('SafeColisHomeBundle:Home:policy.html.twig', array(
            'namepage'=>$namepage,
            'policy'=>$currrentPolicy));
    }

    public function terms_usersAction(Request $request)
    {
        $namepage = "Condition d'utilisation";

        $term = new Userterms();

        $em = $this->getDoctrine()->getManager(); 

        $termpage = $em->getRepository('SafeColisHomeBundle:Userterms')->findAll(); 

        if(empty($termpage))
        {
            $request->getSession()->getFlashBag()->add('term_emplty', 'Condition d\'utilisation vide.');
            $currrentTerm = ' ';

            if($request->isMethod('POST'))
            {
                $term->setDateEdit($_POST['date_edit']);
                $term->setContent($_POST['content']);
                $em->persist($term);
                $em->flush();
                $request->getSession()->getFlashBag()->add('term_edit', 'Condition d\'utilisation crée avec success.');
                return $this->redirectToRoute('safe_colis_home_term');

            }

        }
        else{
            foreach($termpage as $key)
            {

            }
            $currrentTerm = $key;

            if($request->isMethod('POST'))
            {
                $key->setDateEdit($_POST['date_edit']);
                $key->setContent($_POST['content']);
                $em->persist($key);
                $em->flush();
                $request->getSession()->getFlashBag()->add('term_edit', 'Condition d\'utilisation crée avec success.');
                return $this->redirectToRoute('safe_colis_home_term');

            }

        }

        return $this->render('SafeColisHomeBundle:Home:termsuser.html.twig', array(
            'namepage'=>$namepage,
            'term'=>$currrentTerm));
    }


    public function contactAction()
    {
        $namepage = "Contact";

        return $this->render('SafeColisHomeBundle:Home:contact.html.twig', array(
            'namepage'=>$namepage
        ));
    }


    public function mailHelpAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager(); 


        $email = $_POST['email'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];


        if(!$email || !$description){
            $request->getSession()->getFlashBag()->add('send_mail_help_champs_vide', 'Veuillez renseigner tout les champs');
            return $this->redirectToRoute('safe_colis_core_contact');
        }

        $transport = \Swift_SmtpTransport::newInstance()
            ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
            ->setHost($this->getParameter('mailer_host'))
            ->setPort(587)->setEncryption('tls');

        $mailer = \Swift_Mailer::newInstance($transport);
                
                            
        $mail = \Swift_Message::newInstance()
            ->setSubject('SafeColis - '. $titre)
            ->setFrom(array($email => 'SafeColis'))
            ->setTo(array('service@safecolis.com' => 'service@safecolis.com' , $email=>$email  ))
              ->setBody(
                    $this->renderView(
                       'SafeColisHomeBundle:Mail:contact_send_mail.html.twig',array(
                                'description'=>$description,
                                'titre'=>$titre,
                                'email'=>$email
                            )
                            ),
                            'text/html'
                        )
                        ;
                    ;

        $result = $mailer->send($mail);

        if($result){
            $request->getSession()->getFlashBag()->add('send_mail_help_success', 'Votre mail a bien été transmis');
            return $this->redirectToRoute('safe_colis_home_homepage');
        }
        else{
            $request->getSession()->getFlashBag()->add('send_mail_help_fail', 'L\'envoie du mail a echoué.');
            return $this->redirectToRoute('safe_colis_home_contact');

        }
    }


}
