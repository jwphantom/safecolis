<?php

namespace SafeColis\AbonnementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use SafeColis\AbonnementBundle\Entity\Abonnement;
use SafeColis\HomeBundle\Entity\Policy;


class EndSubscriptionController extends Controller
{

    public function endsubscriptionAction(Request $request)
    {

        $namepage = "Resilier son abonnement";
        $email = $this->getUser()->getEmail();

        \Stripe\Stripe::setApiKey("sk_test_xLCPtuEByDif0owIwknZ6mFc");


        $em = $this->getDoctrine()->getManager();

        $abonnement = $em->getRepository('SafeColisAbonnementBundle:Abonnement')->findOneBy(array(
            'user' => $this->getUser()
        ));

        $abonnement->setRenouvellement(0);

        $em->persist($abonnement);
        $em->flush();

        $customers = \Stripe\Customer::all([
            'limit'=>1,
            'email'=>$email
        ]);

        foreach($customers as $customer)
        {
        }


        $subscriptions = \Stripe\Subscription::all(array(
        
            "customer" => $customer->id,
           
        ));

        foreach($subscriptions as $subscription)
        {
        }

        
        
        $subscription = \Stripe\Subscription::retrieve($subscription->id);
        
        $cancel = \Stripe\Subscription::update(
            $subscription->id,
            ['cancel_at_period_end' => true] );


        $transport = \Swift_SmtpTransport::newInstance()
            ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
            ->setHost($this->getParameter('mailer_host'))
            ->setPort(587)->setEncryption('tls');
    
        $mailer = \Swift_Mailer::newInstance($transport);
                            
                                        
        $mail = \Swift_Message::newInstance()
            ->setSubject('SafeColis - '. $namepage)
            ->setFrom(array($email => 'SafeColis'))
            ->setTo(array($email=>$email))
            ->setBody(
                $this->renderView(
                'SafeColisAbonnementBundle:Mail:end_subscribe_mail.html.twig',array(                                
                'date_fin'=>$subscription['current_period_end'],                                    
                'email'=>$email
                )
                ),
                'text/html'
                )
                ;
                ;
    
        $result = $mailer->send($mail);
      
        $response = new Response(json_encode(array(
            'message' => "Votre abonnement sera annulé à la fin de la periode de facturation",
        )));            
        $response->headers->set('Content-Type', 'application/json');
        

        return $response;


             


        // if($request->isMethod('POST'))
        // {

        //     \Stripe\Stripe::setApiKey("sk_test_xLCPtuEByDif0owIwknZ6mFc");
        //     $token = $_POST['stripeToken'];

    
        //     try 
        //     {
                              
        //         $customers = \Stripe\Customer::all([
        //             'limit'=>1,
        //             'email'=>$this->getUser()->getEmail()
        //         ]);

        //         foreach($customers as $customer)
        //         {
        //         }


        //         $subscriptions = \Stripe\Subscription::all(array(
                
        //             "customer" => $customer->id,
                   
        //         ));

        //         foreach($subscriptions as $subscription)
        //         {
        //         }

                
                
        //         $subscription = \Stripe\Subscription::retrieve($subscription->id);
                
        //         \Stripe\Subscription::update($subscription->id, [
        //         'cancel_at_period_end' => false,
        //         'items' => [
        //             [
        //             'id' => $subscription->items->data[0]->id,
        //             'plan' => $id,
        //             ],
        //         ],
        //         ]);
                
        //         $subscription = \Stripe\Subscription::retrieve($subscription->id);


                
        //         $abonnement = $em->getRepository('SafeColisAbonnementBundle:Abonnement')->findOneBy(array(
        //             'user' => $this->getUser()
        //         ));

        //         $abonnement->setDateDebut($subscription['current_period_start']);
        //         $abonnement->setDateFin($subscription['current_period_end']);
        //         $abonnement->setPlan($selectPlan);
        //         $abonnement->setActive(1);

        //         $em->persist($abonnement);
        //         $em->flush();

        //         $transport = \Swift_SmtpTransport::newInstance()
        //             ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
        //             ->setHost($this->getParameter('mailer_host'))
        //             ->setPort(587)->setEncryption('tls');

        //         $mailer = \Swift_Mailer::newInstance($transport);
                        
                                    
        //         $mail = \Swift_Message::newInstance()
        //             ->setSubject('SafeColis - '. $namepage)
        //             ->setFrom(array($email => 'SafeColis'))
        //             ->setTo(array($email=>$email))
        //             ->setBody(
        //                     $this->renderView(
        //                     'SafeColisAbonnementBundle:Mail:create_subscribe_mail.html.twig',array(
        //                                 'plan'=>$selectPlan,
        //                                 'duree'=>$interval,
        //                                 'date_debut'=>$subscription['current_period_start'],
        //                                 'date_fin'=>$subscription['current_period_end'],
        //                                 'amount'=>$amount,
        //                                 'email'=>$email
        //                             )
        //                             ),
        //                             'text/html'
        //                         )
        //                         ;
        //                     ;

        //         $result = $mailer->send($mail);
        //         $request->getSession()->getFlashBag()->add('paiement-abonnenement-success', 'Paiement effectué');
        //         return $this->redirectToRoute('safe_colis_abonnement_status');
    
        //     } 
        //     catch(\Stripe\Error\Card $e) 
        //     {
             
        //         $request->getSession()->getFlashBag()->add('paiement-abonnenement-annule', $err['message']);
        //         return $this->redirectToRoute('safe_colis_abonnement_status');
    
        //     } 
        //     catch (\Stripe\Error\RateLimit $e) 
        //     {
                
        //         $request->getSession()->getFlashBag()->add('paiement-abonnement-annule-time-limit', 'Paiment reservtion annulé L\'opération a depassé le temps necessaire');
        //         return $this->redirectToRoute('safe_colis_abonnement_status');
    
        //     } 
        //     catch (\Stripe\Error\InvalidRequest $e) 
        //     {
        //         return "connection au api a echoue";
        //       } catch (\Stripe\Error\Authentication $e) {
        //         // Authentication with Stripe's API failed
        //         // (maybe you changed API keys recently)
        //       } catch (\Stripe\Error\ApiConnection $e) {
        //         // Network communication with Stripe failed
        //       } catch (\Stripe\Error\Base $e) {
        //         // Display a very generic error to the user, and maybe send
        //         // yourself an email
        //       } catch (Exception $e) {
        //         // Something else happened, completely unrelated to Stripe
        //       }



        // }
       
    }

}