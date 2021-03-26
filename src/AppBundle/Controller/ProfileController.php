<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ProfileController extends Controller
{

     /**
     * @Route("/profile", name="profile_view")
     */
    public function viewAction(Request $request)
    {
        $user = $this->getUser();
        $namepage = "Profile";
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {

            if(isset($_POST['username']))
            {
                $user->setUsername($_POST['username']);
            }

            if(isset($_POST['nom']))
            {
                $user->setNom($_POST['nom']);
            }

            if(isset($_POST['prenom']))
            {
                $user->setPrenom($_POST['prenom']);

            }

            $request->getSession()->getFlashBag()->add('edit_profile', 'Profile modifié.');
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('profile_view', array('namepage'=>$namepage));


        }
        return $this->render('AppBundle:Profile:view.html.twig', array(
            'namepage'=>$namepage
        ));
    }
}