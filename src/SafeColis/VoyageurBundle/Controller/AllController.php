<?php

namespace SafeColis\VoyageurBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//entié
use SafeColis\VoyageurBundle\Entity\Voyageur;

//form
use SafeColis\VoyageurBundle\Form\VoyageurType;

//api cloudinary
use Cloudinary\Uploader;


class AllController extends Controller
{
    public function allAction(Request $request)
    {
        $namepage = "Listes des voyages";

        return $this->render('SafeColisVoyageurBundle:all:index.html.twig', array(
            'namepage'=>$namepage,
        ));

    }
}