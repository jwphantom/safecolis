<?php

namespace SafeColis\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuReservationController extends Controller
{
    public function menuAction()
    {

        $namepage = "Reservation";
        return $this->render('SafeColisReservationBundle:Reservation:choice.html.twig', array(
            'namepage'=>$namepage
        ));
    }
}
