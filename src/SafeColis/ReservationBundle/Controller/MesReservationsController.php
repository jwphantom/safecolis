<?php

namespace SafeColis\ReservationBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//entié


class MesReservationsController extends Controller
{

    //variable qui contient le nom de la page
    private $namepage;
    //entity manager
    private $em;

    public function allAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 

        $user = $this->getUser();
        $namepage = "Mes reservations";

        $reservation = $em->getRepository('SafeColisExpedieurBundle:Reservation')->findBy(array(
            'idreserveur'=>$user->getId()
        ));        
        return $this->render('SafeColisReservationBundle:Reservation:mesreservations.html.twig', array(
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
                ->setSubject('SafeColis - '. "Suppression de reservation")
                ->setFrom(array($this->getParameter('mailer_user') => 'SafeColis'))
                ->setTo(array( $reservation->getVoyage()->getUser()->getEmail() =>$reservation->getVoyage()->getUser()->getEmail(), $this->getUser()->getEmail() => $this->getUser()->getEmail() ))
                ->setBody(
                        $this->renderView(
                        'SafeColisExpedieurBundle:Mail:del_reservation.html.twig',array(
                                    'kilovoulu'=>$reservation->getKilovoulu(),
                                    'depart'=>$reservation->getVoyage()->getVilledepart(),
                                    'arrive'=>$reservation->getVoyage()->getVillearrive(),
                                    'datedepart'=>$reservation->getVoyage()->getDatedepart(),
                                    'voyageur'=>$reservation->getVoyage()->getUser()->getEmail())
                                ),
                                'text/html'
                            )
                            ;
                        ;
            
            $em->flush();
            $result = $mailer->send($mail);
            $request->getSession()->getFlashBag()->add('suppression_reservation', 'Reservation supprimée.');
            return $this->redirectToRoute('safe_colis_mes_reservation');
     

    }
}
