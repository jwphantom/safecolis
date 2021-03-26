<?php

namespace SafeColis\ReservationBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//entié


class MesDemandesController extends Controller
{

    //variable qui contient le nom de la page
    private $namepage;
    //entity manager
    private $em;

    public function allAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 

        $user = $this->getUser();
        $namepage = "Mes demandes";

        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SafeColisExpedieurBundle:Reservation');
            
                $reservation = $repository->searchVoyageur($user->getId());

                
        return $this->render('SafeColisReservationBundle:Reservation:mesdemandes.html.twig', array(
            'namepage'=>$namepage,
            'result'=>$reservation
        ));
    }

    public function delAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager(); 
        $reservation = $em->getRepository('SafeColisExpedieurBundle:Reservation')->find($id); 

        $em->remove($reservation);
        $transport = \Swift_SmtpTransport::newInstance()
        ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
        ->setHost($this->getParameter('mailer_host'))
        ->setPort(587)->setEncryption('tls');

        $mailer = \Swift_Mailer::newInstance($transport);
                
                            
        $mail = \Swift_Message::newInstance()
            ->setSubject('SafeColis - '. "Reservation accepté")
            ->setFrom(array($this->getParameter('mailer_user') => 'SafeColis'))
            ->setTo(array( $reservation->getIdreserveur()->getEmail() =>$reservation->getIdreserveur()->getEmail()))
            ->setBody(
                $this->renderView(
                'SafeColisReservationBundle:Mail:refuse_reservation.html.twig',array(
                            'kilovoulu'=>$reservation->getKilovoulu(),
                            'depart'=>$reservation->getVoyage()->getVilledepart(),
                            'arrive'=>$reservation->getVoyage()->getVillearrive(),
                            'datedepart'=>$reservation->getVoyage()->getDatedepart(),
                            'reserveur'=>$reservation->getIdreserveur()->getEmail() )
                        ),
                        'text/html'
                    )
                    ;
                ;

                        
                    

        $em->flush();
        $result = $mailer->send($mail);
        $em->flush();
        $request->getSession()->getFlashBag()->add('suppression_reservation', 'Reservation supprimée.');
        return $this->redirectToRoute('safe_colis_mes_reservation');
     

    }
    public function acceptAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager(); 
        $reservation = $em->getRepository('SafeColisExpedieurBundle:Reservation')->find($id); 
        $reservation->setEtat(1);

        $updatekilo = $em->getRepository('SafeColisVoyageurBundle:Voyageur')->find($reservation->getVoyage());     
        
        $kilodispo = $updatekilo->getKilodisponible() - $reservation->getKiloVoulu();
        $kilovendu = $updatekilo->getKilovendu() + $reservation->getKiloVoulu();

        if ($kilodispo < 0)
        {
           
            $request->getSession()->getFlashBag()->add('kilo_overflow', 'Vous n\'avez plus de kilo disponible sur ce voyage.');
            return $this->redirectToRoute('safe_colis_mes_demandes');

        }
        

        $updatekilo->setKilovendu($kilovendu);
        $updatekilo->setKilodisponible($kilodispo);
        $em->persist($reservation);
        $em->persist($updatekilo);

        $transport = \Swift_SmtpTransport::newInstance()
        ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
        ->setHost($this->getParameter('mailer_host'))
        ->setPort(587)->setEncryption('tls');

        $mailer = \Swift_Mailer::newInstance($transport);
                
                            
        $mail = \Swift_Message::newInstance()
            ->setSubject('SafeColis - '. "Reservation accepté")
            ->setFrom(array($this->getParameter('mailer_user') => 'SafeColis'))
            ->setTo(array( $reservation->getIdreserveur()->getEmail() =>$reservation->getIdreserveur()->getEmail()))
            ->setBody(
                    $this->renderView(
                    'SafeColisReservationBundle:Mail:accept_reservation.html.twig',array(
                                'kilovoulu'=>$reservation->getKilovoulu(),
                                'depart'=>$reservation->getVoyage()->getVilledepart(),
                                'arrive'=>$reservation->getVoyage()->getVillearrive(),
                                'datedepart'=>$reservation->getVoyage()->getDatedepart(),
                                'montant'=> $reservation->getKiloVoulu()*$reservation->getVoyage()->getPrixkilo(),
                                'reserveur'=>$reservation->getIdreserveur()->getEmail(),
                                'prix_mise_relation'=>5.75,
                                'lien_paiement'=>'http://'.$_SERVER['HTTP_HOST'].'/reservation/pay/'.$reservation->getId() )
                            ),
                            'text/html'
                        )
                        ;
                    ;

        $em->flush();
        $result = $mailer->send($mail);
        $request->getSession()->getFlashBag()->add('reservation_accepte', 'Reservation accepté.');
        return $this->redirectToRoute('safe_colis_mes_demandes');
     

    }
}
