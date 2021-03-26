<?php

namespace SafeColis\ExpedieurBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//entié


class SearchController extends Controller
{

    //variable qui contient le nom de la page
    private $namepage;
    //entity manager
    private $em;

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 

        $namepage = "Rechercher un voyage";

        

        return $this->render('SafeColisExpedieurBundle:Search:search.html.twig', array(
            'namepage'=>$namepage,
        ));
    }
}
