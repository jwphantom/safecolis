<?php

namespace SafeColis\AbonnementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use SafeColis\AbonnementBundle\Entity\Abonnement;
use SafeColis\HomeBundle\Entity\Policy;


class ReSubscriptionController extends Controller
{

    public function webhookAction(){

        http_response_code(200);


        \Stripe\Stripe::setApiKey("sk_test_xLCPtuEByDif0owIwknZ6mFc"); // Your Stripe API Secret Key

        // Retrieve the request's body and parse it as JSON
        $input = file_get_contents("php://input");
        $event_json = json_decode($input);

        $event = \Stripe\Event::retrieve($event_json->id);

        $email = $event->data->object->customer_email;

        $dateFin = $event->data->object->lines->data[0]->period->end;
        $dateDebut = $event->data->object->lines->data[0]->period->start;
        $plan = $event->data->object->lines->data[0]->plan->nickname;
        $interval = $event->data->object->lines->data[0]->plan->interval;
        $amount = $event->data->object->lines->data[0]->plan->amount;




        // if($event->type == "invoice.payment_succeeded") {
      
        //     $em = $this->getDoctrine()->getManager();


        //     $user = $em->getRepository('AppBundle:User')->findOneBy(array(
        //          'email' => $email
        //     ));

        //     $abonnement = $em->getRepository('SafeColisAbonnementBundle:Abonnement')->findOneBy(array(
        //          'user' => $user
        //     ));

        //     $abonnement->setUser($user);
        //     $abonnement->setPlan($plan);
        //     $abonnement->setDateDebut($dateDebut);
        //     $abonnement->setDateFin($dateFin);
        //     $abonnement->setActive(1);

        //     $em->persist($abonnement);
        //     $em->flush();

        //     $transport = \Swift_SmtpTransport::newInstance()
        //         ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
        //         ->setHost($this->getParameter('mailer_host'))
        //         ->setPort(587)->setEncryption('tls');
        //     $mailer = \Swift_Mailer::newInstance($transport);
                                           
        //     $mail = \Swift_Message::newInstance()
        //         ->setSubject('SafeColis - '. 'Payement de l\'abonnement')
        //         ->setFrom(array('service@safecolis.com' => 'SafeColis'))
        //         ->setTo(array($email=>$email))
        //         ->setBody(
        //             $this->renderView(
        //             'SafeColisAbonnementBundle:Mail:create_subscribe_mail.html.twig',array(
        //                 'plan'=>$plan,
        //                 'duree'=>$interval,
        //                 'date_debut'=>$dateDebut,
        //                 'date_fin'=>$dateFin,
        //                 'amount'=>$amount/100,
        //                 'email'=>$email
        //                             )
        //                             ),
        //                             'text/html'
        //                         )
        //                         ;
        //                     ;

        //         $result = $mailer->send($mail);
               
        // }

        
        if($event->type == "invoice.payment_failed") {
      
            $em = $this->getDoctrine()->getManager();


            $user = $em->getRepository('AppBundle:User')->findOneBy(array(
                 'email' => $email
            ));

            $abonnement = $em->getRepository('SafeColisAbonnementBundle:Abonnement')->findOneBy(array(
                 'user' => $user
            ));

            $abonnement->setUser($user);
            $abonnement->setPlan("Aucun");
            $abonnement->setDateDebut("");
            $abonnement->setDateFin("");
            $abonnement->setActive(0);

            $em->persist($abonnement);
            $em->flush();

            $transport = \Swift_SmtpTransport::newInstance()
                ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
                ->setHost($this->getParameter('mailer_host'))
                ->setPort(587)->setEncryption('tls');
            $mailer = \Swift_Mailer::newInstance($transport);
                                           
            $mail = \Swift_Message::newInstance()
                ->setSubject('SafeColis - '. 'Abonnement - Safecolis')
                ->setFrom(array('service@safecolis.com' => 'SafeColis'))
                ->setTo(array($email=>$email))
                ->setBody(
                    $this->renderView(
                        'SafeColisAbonnementBundle:Mail:unsubscribe.html.twig',array(
                            'plan'=>"Aucun",
                            
                            'email'=>$email
                                        )
                                        ),
                                        'text/html'
                                    )
                                    ;
                                ;
    
                    $result = $mailer->send($mail);
               
        }


        var_dump($event);

    }
}