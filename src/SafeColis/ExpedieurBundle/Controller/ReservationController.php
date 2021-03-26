<?php

namespace SafeColis\ExpedieurBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//entié
use SafeColis\ExpedieurBundle\Entity\Reservation;


class ReservationController extends Controller
{

    //variable qui contient le nom de la page
    private $namepage;
    //entity manager
    private $em;
    
    private $reservation;

    private $user;

    public function reserverAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 

        $user = $this->getUser();
        $reservation = new Reservation();
        
        $namepage = "Rechercher un voyage";

        if ($request->isMethod('POST')) {
    
            $description = '';
            foreach( $_POST['colis'] as $key)
            {
                $description = $description. $key.', ';
            }

            $voyage = $em->getRepository('SafeColisVoyageurBundle:Voyageur')->find($_POST['idvoyage']);     

            $reservation->setKiloVoulu($_POST['kilovoulu']);
            $reservation->setDateajout(new \Datetime());
            $reservation->setIdreserveur($user);
            $reservation->setVoyage($voyage);
            $reservation->setDescription('Type de colis a transporter : '.$description);
            $reservation->setEtat(0);
            $reservation->setPaye(0);

            $em->persist($reservation);

            $transport = \Swift_SmtpTransport::newInstance()
            ->setUsername($this->getParameter('mailer_user'))->setPassword($this->getParameter('mailer_password'))
            ->setHost($this->getParameter('mailer_host'))
            ->setPort(587)->setEncryption('tls');

            $mailer = \Swift_Mailer::newInstance($transport);
                    
                                
            $mail = \Swift_Message::newInstance()
                ->setSubject('SafeColis - '. "Demande de reservation")
                ->setFrom(array($this->getParameter('mailer_user') => 'SafeColis'))
                ->setTo(array( $voyage->getUser()->getEmail() =>$voyage->getUser()->getEmail(), $this->getUser()->getEmail() => $this->getUser()->getEmail() ))
                ->setBody(
                        $this->renderView(
                        'SafeColisExpedieurBundle:Mail:ask_reservation.html.twig',array(
                                    'kilovoulu'=>$_POST['kilovoulu'],
                                    'depart'=>$voyage->getVilledepart(),
                                    'arrive'=>$voyage->getVillearrive(),
                                    'datedepart'=>$voyage->getDatedepart(),
                                    'voyageur'=>$voyage->getUser()->getEmail())
                                ),
                                'text/html'
                            )
                            ;
                        ;
            
            $em->flush();
            $result = $mailer->send($mail);

            $request->getSession()->getFlashBag()->add('reservation_effectue', 'Reservation envoyé au voyaguer. veuillez patientez sa réponde');

            return $this->redirectToRoute('safe_colis_mes_reservation');
        }

        

        return $this->render('SafeColisExpedieurBundle:Search:search.html.twig', array(
            'namepage'=>$namepage,
        ));
    }
}
