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

//Ajout d'un voyage dans la bd
class AddController extends Controller
{

    //variable qui contient le nom de la page
    private $namepage;
    //variable qui contient l'utilisateur courat
    private $user;
    //variable d'enregistrement du voyage
    private $voyageur;
    
    public function addAction(Request $request, $mode)
    {

        $em = $this->getDoctrine()->getManager();
        $abonnement = $em->getRepository('SafeColisAbonnementBundle:Abonnement')->findOneBy(array(
            'user' => $this->getUser()
        ));

        $currenttimestamp = new \Datetime();

        $test = $currenttimestamp->format('U');


        if(!isset($abonnement) || $abonnement->getActive() == false || $abonnement->getDateFin() < $test)
        {

            $request->getSession()->getFlashBag()->add('not_found_subscription', 'Vous n\'êtes pas encore abonné.');
            return $this->redirectToRoute('safe_colis_abonnement_status');
        }

        //nom de la page dans une variable
        $namepage = "Ajouter son voyage";

        //recupération de l'utilisateru courant
        $user = $this->getUser();

        /* si l'utilisateur n'a pas encore completer ses informations sur son profile on le 
        redirige pour completion*/
        if(!$user->getNom() && !$user->getPrenom())
        {
            $request->getSession()->getFlashBag()->add('completer_son_profil', 'Veuillez Renseigner votre nom et prenom pour pouvoir ajouter un voyage');
            return $this->redirectToRoute('profile_view');
        }

        //instancier la classe voyageur pour enregistrement des informations de voyage
        $voyageur = new Voyageur();  
        
        //si le formulaire est remplie et validé on enregistre les informations
        $form   = $this->get('form.factory')->create(VoyageurType::class, $voyageur);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())  {
            
            $heureDepart = $_POST['heuredepart'];
            $dateDepart = $_POST['dateDepart'];
            $kilodispo = $_POST['kilodispo'];
            $prixkilo = $_POST['prixkilo'];
                    
            $voyageur->setUser($user);
            $voyageur->setHeuredepart($heureDepart);
            $voyageur->setKilodisponible($kilodispo);
            $voyageur->setPrixkilo($prixkilo);
            $voyageur->setDateajout(new \DateTime());
            $voyageur->setActive(0);
            $voyageur->setKilovendu(0);
            $voyageur->setDatedepart($dateDepart);
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($voyageur);
            $em->flush();

            $pathtransporteur = $em->getRepository('SafeColisVoyageurBundle:Voyageur')->find($voyageur);     
            $identificationpath = $voyageur->getIdentification()->getwebPath();
            $justificationpath = $voyageur->getJustification()->getwebPath();
            $idvoyageur = $user->getId();

            \Cloudinary::config(array( 
                "cloud_name" => "doxl9x2a0", 
                "api_key" => "439711648162288", 
                "api_secret" => "J9xXs3_uer-x7SKng_BExnrqZ-8", 
                "secure" => true
              ));

            if($mode == "aerien")
            {
                // on enregistre la piece d'identification dans cloudinary
                \Cloudinary\Uploader::upload($identificationpath, array(
                    "folder" => 'Voyageur/'.$idvoyageur."/passport/",
                    "public_id" => $voyageur->getIdentification()->getId()
                    ));

                // on enregistre la piece de justififcation de voyage dans cloudinary
                \Cloudinary\Uploader::upload($justificationpath, array(
                    "folder" => 'Voyageur/'.$idvoyageur."/billet_avion/",
                    "public_id" => $voyageur->getJustification()->getId()
                    ));

                $pathtransporteur->setUrlIdentification("Voyageur/".$idvoyageur."/passport/". $voyageur->getIdentification()->getId());
                $pathtransporteur->setUrlJustification("Voyageur/".$idvoyageur."/billet_avion/". $voyageur->getJustification()->getId());
                $em->persist($pathtransporteur);
                $em->flush();
            }
            elseif($mode == "routier"){
                // on enregistre la piece d'identification dans cloudinary
                \Cloudinary\Uploader::upload($identificationpath, array(
                    "folder" => 'Voyageur/'.$idvoyageur."/carte_identité/",
                    "public_id" => $voyageur->getIdentification()->getId()
                    ));

                // on enregistre la piece de justififcation de voyage dans cloudinary
                \Cloudinary\Uploader::upload($justificationpath, array(
                    "folder" => 'Voyageur/'.$idvoyageur."/permis_conduit/",
                    "public_id" => $voyageur->getJustification()->getId()
                    ));

                $pathtransporteur->setUrlIdentification("Voyageur/".$idvoyageur."/carte_identité/". $voyageur->getIdentification()->getId());
                $pathtransporteur->setUrlJustification("Voyageur/".$idvoyageur."/permis_conduit/". $voyageur->getJustification()->getId());
                $em->persist($pathtransporteur);
                $em->flush();
            }
            else{
                // on enregistre la piece d'identification dans cloudinary
                \Cloudinary\Uploader::upload($identificationpath, array(
                    "folder" => 'Voyageur/'.$idvoyageur."/carte_identité/",
                    "public_id" => $voyageur->getIdentification()->getId()
                    ));

                // on enregistre la piece de justififcation de voyage dans cloudinary
                \Cloudinary\Uploader::upload($justificationpath, array(
                    "folder" => 'Voyageur/'.$idvoyageur."/ticket_transport/",
                    "public_id" => $voyageur->getJustification()->getId()
                    ));
                   
                $pathtransporteur->setUrlIdentification("Voyageur/".$idvoyageur."/carte_identité/". $voyageur->getIdentification()->getId());
                $pathtransporteur->setUrlJustification("Voyageur/".$idvoyageur."/ticket_transport/". $voyageur->getJustification()->getId());
                $em->persist($pathtransporteur);
                $em->flush();
            }
           

            $request->getSession()->getFlashBag()->add('ajout-voyage', 'Examination de l\'annonce par la Direction.');
            return $this->redirectToRoute('safe_colis_voyageur_choice_transport');
        }

        return $this->render('SafeColisVoyageurBundle:add:add.html.twig', array(
            'namepage'=>$namepage,
            'mode'=>$mode,
            'form' => $form->createView()
        ));
    }
}
