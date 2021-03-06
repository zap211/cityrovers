<?php

namespace CTRV\CommonBundle\Controller;

use CTRV\CommonBundle\Form\RegistrationType;

use Symfony\Component\Security\Core\SecurityContext;

use CTRV\CommonBundle\Form\UserAuthType;

use Symfony\Component\HttpFoundation\Response;

use CTRV\CommonBundle\DependencyInjection\Constants;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CTRV\CommonBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;


/**
 * User controller.
 *
 * 
 */
class AuthController extends Controller
{
    
    /**
     * Affiche le formulaire de connexion
     * @Template()
     */
    public function loginAction () {
    	
    	$request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error
        	);
    }
    
    /**
     * Affiche la page indiquant que vous n'avez pas les droits d'acces
     * @Route("/insufficient_access",name="insufficient_access")
     * @Template()
     */
    public function insufficientAccessAction () {
    	
    	$referer = $this->getRequest()->headers->get('referer');
    	return array("url"=>$referer);
    }
    
    /**
     * Afficher et traite le formulaire d'inscription d'un utilisateur
     * @Route("/register",name="register")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function registerAction () {
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$form = $this->createForm(new RegistrationType(), new User());
    	
    	if ($this->getRequest()->getMethod()=="POST") {
    		
	    	$form->bind($this->getRequest());
	    	
	    	if ($form->isValid()) {
	    		
	    		$user = $form->getData();
	    		$userid = hash('ripemd160',$user->getLogin().$user->getEmail().date('d/m/y-H:i:s'));
	    		
	    		$encoder = $this->get('password_service');
	    		
	    		$role = $this->getDoctrine()->getEntityManager()->getRepository("CTRVCommonBundle:Role")->findOneBy(array("name"=>Constants::ROLE_USER));
	    		
	    		$user->setSalt(uniqid(mt_rand()));
	    		$user->setRole($role);
	    		$user->setUserid($userid);
	    		$user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));
	    		$user->setIsActive(true);
	    		$user->setIsBlocked(false);
	    		
	    		$em->persist($user);
	    		$em->flush();
	    	
	    		return $this->redirect($this->generateUrl("home"));
	    	}
    	}
    	
    	return array('form' => $form->createView());
    }
    

}
