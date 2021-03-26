<?php

namespace SafeColis\ReservationBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//entié


class PayReservationController extends Controller
{

    //variable qui contient le nom de la page
    private $namepage;
    //entity manager
    private $em;

    public function payAction(Request $request, $id)
    {
        $namepage = "Paiement de votre reservation";

        $em = $this->getDoctrine()->getManager(); 

        $user = $this->getUser();

        $reservation = $em->getRepository('SafeColisExpedieurBundle:Reservation')->find($id);


        if($request->isMethod('POST'))
        {
            \Stripe\Stripe::setApiKey("sk_test_xLCPtuEByDif0owIwknZ6mFc");
            $token = $_POST['stripeToken'];

            try{

                $charge = \Stripe\Charge::create([
                    'amount' => 5.75*100,
                    'currency' => 'usd',
                    'description' => 'Paiement Reservation',
                    'source' => $token,
                    'metadata' => ['id_reservation' => $reservation->getId(),
                "email"=>$this->getUser()->getEmail(),
                "nom"=> $_POST['name']],
                ]);

                if($charge['status'] == "succeeded")
                { 

                    $reservation->setPaye(1);
                    $transport = \Swift_SmtpTransport::newInstance()
                        ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
                        ->setHost($this->getParameter('mailer_host'))
                        ->setPort(587)->setEncryption('tls');


                    $mailer = \Swift_Mailer::newInstance($transport);
                    
                            
                    $mail1 = \Swift_Message::newInstance()
                        ->setSubject("SafeColis - Votre Reçu de Transaction")
                        ->setFrom(array('service@safecolis.com' => 'SafeColis'))
                        ->setTo(array( $user->getEmail() => $user->getEmail() ))
                        ->setBody(
                            $this->renderView(
                            'SafeColisReservationBundle:Mail:facture_reservation.html.twig',array(
                                'nom'=> $user->getEmail(),
                                'date_transaction'=>$charge['created'],
                                'reference'=>$id,
                                'nom_voyageur'=> $reservation->getVoyage()->getUser()->getNom(),
                                'email_voyageur'=> $reservation->getVoyage()->getUser()->getEmail(),
                                'numero_Tel'=>$reservation->getVoyage()->getUser()->getUsername(),
                                'depart'=> $reservation->getVoyage()->getVilledepart(),
                                'arrive'=>$reservation->getVoyage()->getVillearrive(),
                                'date'=>$reservation->getVoyage()->getDatedepart(),
                                'heure'=>$reservation->getVoyage()->getHeuredepart(),
                                'kilo_achete'=>$reservation->getKiloVoulu(),
                                'prix_reservation'=>"5.75",
                                'somme_voyageur'=>$reservation->getKiloVoulu() * $reservation->getVoyage()->getPrixkilo(),
                            )
                            ),
                            'text/html'
                        )
                        ;
                    ;

                    $mail2 = \Swift_Message::newInstance()
                        ->setSubject("SafeColis - Details du Voyage")
                        ->setFrom(array('service@safecolis.com' => 'SafeColis'))
                        ->setTo(array( $user->getEmail() => $user->getEmail() ))
                        ->setBody(
                            $this->renderView(
                            'SafeColisReservationBundle:Mail:details_voyageurs.html.twig',array(
                                'nom_voyageur'=> $reservation->getVoyage()->getUser()->getEmail(),
                                'nom'=> $user->getNom().' '.$user->getPrenom(),
                                'email_voyageur'=> $reservation->getVoyage()->getUser()->getEmail(),
                                'numero_Tel'=>$reservation->getVoyage()->getUser()->getUsername(),
                                'depart'=> $reservation->getVoyage()->getVilledepart(),
                                'arrive'=>$reservation->getVoyage()->getVillearrive(),
                                'date'=>$reservation->getVoyage()->getDatedepart(),
                                'heure'=>$reservation->getVoyage()->getHeuredepart(),
                                'kilo_achete'=>$reservation->getKiloVoulu(),
                                'email_voyageur'=> $reservation->getVoyage()->getUser()->getEmail(),
                                'numero_Tel'=>$reservation->getVoyage()->getUser()->getUsername(),
                                'somme_voyageur'=>$reservation->getKiloVoulu() *  $reservation->getVoyage()->getPrixkilo(),
                            )
                            ),
                            'text/html'
                    )
                    ;

                    $mail3 = \Swift_Message::newInstance()
                        ->setSubject("SafeColis - Informations sur l'Expediteur")
                        ->setFrom(array('service@safecolis.com' => 'SafeColis'))
                        ->setTo(array( $reservation->getVoyage()->getUser()->getEmail() => $reservation->getVoyage()->getUser()->getEmail() ))
                        ->setBody(
                            $this->renderView(
                            'SafeColisReservationBundle:Mail:details_envoyeur.html.twig',array(
                                'nom_voyageur'=> $reservation->getVoyage()->getUser()->getNom().' '. $reservation->getVoyage()->getUser()->getPrenom(),
                                'nom_envoyeur'=> $user->getNom().' '.$user->getPrenom(),
                                'email_envoyeur'=> $user->getEmail(),
                                'numero_Tel'=>$user->getUsername(),
                                'depart'=> $reservation->getVoyage()->getVilledepart(),
                                'arrive'=>$reservation->getVoyage()->getVillearrive(),
                                'date'=>$reservation->getVoyage()->getDatedepart(),
                                'heure'=>$reservation->getVoyage()->getHeuredepart(),
                                'kilo_achete'=>$reservation->getKiloVoulu(),
                                'email_voyageur'=> $reservation->getVoyage()->getUser()->getEmail(),
                                'numero_Tel'=>$user->getUsername(),
                                'somme_voyageur'=>$reservation->getKiloVoulu() *  $reservation->getVoyage()->getPrixkilo(),
                            )
                            ),
                            'text/html'
                    )
                    ;
                ;
                        
                    $result1 = $mailer->send($mail1);
                    $result2 = $mailer->send($mail2);
                    $result3 = $mailer->send($mail3);
                    $em->persist($reservation);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('paiement-reservation-effectue', 'Votre paiement a bien été effectué');
                    return $this->redirectToRoute('safe_colis_mes_reservation');
                }
                else{

                    $request->getSession()->getFlashBag()->add('paiement-reservation-annule', "Votre paiement n\'a pas été effectué");
                    return $this->redirectToRoute('safe_colis_mes_reservation');

                }

            }
            catch(\Stripe\Error\Card $e) 
            {
             
                $request->getSession()->getFlashBag()->add('paiement-reservation-annule', $err['message']);
                return $this->redirectToRoute('safe_colis_mes_reservation');
    
            } 
            catch (\Stripe\Error\RateLimit $e) 
            {
                
                $request->getSession()->getFlashBag()->add('paiement-abonnement-annule-time-limit', 'Paiment reservtion annulé L\'opération a depassé le temps necessaire');
                return $this->redirectToRoute('safe_colis_mes_reservation');
    
            } 
            catch (\Stripe\Error\InvalidRequest $e) 
            {
                return "connection au api a echoue";
              } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
              } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
              } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
              } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
              }
        }

        return $this->render('SafeColisReservationBundle:Pay:pay.html.twig', array(
        'reservation' => $reservation,
        'namepage' => $namepage ));

    }
}