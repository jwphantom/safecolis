<?php

namespace AppBundle\Controller;

use Educcia\UserBundle\User\Password\ChangePassword;
use Educcia\UserBundle\Form\Type\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Educcia\UserBundle\Form\Type\RequestPasswordType;
use Educcia\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Educcia\AdminBundle\Form\EditCoursPDFType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as SecurityUserInterface;



class ChangePasswordController extends Controller
{


    //fonction qui se charge de modifier le mot de passe
    /**
     * @Route("/profile/change-password", name="change_password")
     */
    public function changepasswordAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();

        $namepage = "Modifier son mot de passe";
        //current user
        $c_user = $this->getUser();
        
        $user = $dm->getRepository('AppBundle:User')->findOneBy(array(
            'email' => $c_user->getEmail()
        ));

        if($request->isMethod("post"))
        {
            //recupère le mot de passe
            $old_pass = $_POST['old_password'];
            $pass = $_POST['password'];
            $r_pass = $_POST['repeat_password'];

            
            $encoder = $this->container->get('security.password_encoder');
            $encoded1 = $encoder->encodePassword($user, $old_pass); 
            $old_pass_enter = $encoded1;
            
            //verification de l'ancien mot de passe
            if($old_pass_enter != $user->getPassword())
            {                
                $request->getSession()->getFlashBag()->add('old_pass_diff', 'Ancien mot de passe différent.');
                return $this->redirectToRoute('change_password');
            }

            if($pass != $r_pass)
            {
                $request->getSession()->getFlashBag()->add('mot_passe_different', 'Ancien mot de passe différent.');
                return $this->redirectToRoute('change_password');
            }

            $encoded2 = $encoder->encodePassword($user, $pass); 
            $plainPassword = $encoded2;
            
            $user->setPassword($plainPassword);
            $dm->persist($user);
            $dm->flush();

            $request->getSession()->getFlashBag()->add('password_changed', 'Votre Mot de passe a bien été changé.');
                return $this->redirectToRoute('profile_view');
        }

        return $this->render('AppBundle:Password:changepassword.html.twig', array(
            'namepage'=>$namepage
        ));
    }
}