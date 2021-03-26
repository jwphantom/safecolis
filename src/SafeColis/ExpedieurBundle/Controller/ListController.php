<?php

namespace SafeColis\ExpedieurBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//entié


class ListController extends Controller
{

    //variable qui contient le nom de la page
    private $namepage;
    //entity manager
    private $em;

    public function listAction(Request $request)
    {
        $namepage="Listes des voyages recherchés";

        $colisAutorise = array('argent','appareil','Nourriture','médicament','Produit d\'Hygiène', 'Autres');

        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {

            if(isset($_POST['checkboxDateDepart']))
            {
            

                $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SafeColisExpedieurBundle:Reservation');
            
                $result = $repository->findVoyageWithDate($this->getUser()->getId(), $_POST['lieuDepart'],$_POST['lieuArrive'],$_POST['dateDepart']);

              
            }
            else
            {
                $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SafeColisExpedieurBundle:Reservation');
            
                $result = $repository->findVoyage($this->getUser()->getId(), $_POST['lieuDepart'],$_POST['lieuArrive']);

              
            }             
      

            if(count($result) >= 1 )
            {
                $request->getSession()->getFlashBag()->add('voyage_trouve', 'Voyage(s) trouvé(s).');
                return $this->render('SafeColisExpedieurBundle:Lists:lists.html.twig', array(
                    'result'=> $result,
                    'namepage'=>$namepage,
                    'colisautorise'=>$colisAutorise
                    ));
            }
            else{
                $request->getSession()->getFlashBag()->add('voyage_non_trouve', 'Aucun Voyage(s) trouvé(s).');
                return $this->redirectToRoute('safe_colis_expedieur_recherche_voyage');

            }
        }

    }
}