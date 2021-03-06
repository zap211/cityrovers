<?php

namespace CTRV\MailBundle\Controller;

use CTRV\MailBundle\Form\SendMailType;
use CTRV\CommonBundle\DependencyInjection\Constants;
use CTRV\MailBundle\Entity\MailTemplate;
use CTRV\MailBundle\Entity\MailType;
use CTRV\MailBundle\Form\MailTemplateType;
use CTRV\MailBundle\Form\MailTypeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * Mail controller.
 *
 * @Route("/mail")
 */
class MailController extends Controller
{
    /**
     * @Route("/list_mail",name="mail")
     * @Template()
     */
    public function indexAction() {
    	
    	$entities = $this->getDoctrine()->getEntityManager()->getRepository("CTRVMailBundle:MailTemplate")->findAll();
        return array("entities"=>$entities);
    }
    
    /**
     * @Route("/send",name="send_mail")
     * @Template()
     */
    public function sendMailAction() {
    	
    	$form = $this->createForm(new MailTemplateType());
        $form1 = $this->createForm(new SendMailType());	 
    	if( $this->get('request')->getMethod() == 'POST' ) {
    	    $form1->bind($this->get('request'));
    		$form->bind($this->get('request'));
    	
    		if ( $form->isValid() ) {
    			
    			$mailTemplate = $form->getData();
    			$this->getDoctrine()->getEntityManager()->persist($mailTemplate);
    			$this->getDoctrine()->getEntityManager()->flush();
    			
    			return $this->redirect($this->generateUrl("mail"));
    		}
    		$message = \Swift_Message::newInstance();
    		  
    		  if ($form1->isValid()){
    		     
    		  	 $message->setSubject("Objet");
    		     $message->setFrom('cndiaye@iota-it.com');
    		     $message->setTo('ahmedtijane@gmail.com');
    		     $message->setBody('Hello world');
    		     $this->get('mailer')->send($message);
    		     
    		     return $this->redirect($this->generateUrl("send_mail"));
    		}
    	}
    	return array(
    			'form'=>$form->createView(),
    			'form1'=>$form1->createView()
    			);
    }
    
    
    /**
     * Ajouter, sauvegarder un mail template
     * @Route("/mailTemplate/add",name="add_mail")
     * @Template()
     */
    public function addMailTemplateAction() {
    	 
    	$form = $this->createForm(new MailTemplateType());
    	 
    	if( $this->get('request')->getMethod() == 'POST' ) {
    	
    		$form->bind($this->get('request'));
    	
    		if ( $form->isValid() ) {
    			
    			$mailTemplate = $form->getData();
    			$this->getDoctrine()->getEntityManager()->persist($mailTemplate);
    			$this->getDoctrine()->getEntityManager()->flush();
    			
    			return $this->redirect($this->generateUrl("mail"));
    		}
    	}
    	return array('form'=>$form->createView());
    }
    
    /**
     * Editer puis sauvegarder un mail template
     * @Route("/mailTemplate/edit/{id}",name="edit_mail")
     * @Template()
     */
    public function editMailTemplateAction (MailTemplate $mailTemplate) {
    
    	$form = $this->createForm(new MailTemplateType,$mailTemplate);
    	
    	if( $this->get('request')->getMethod() == 'POST' ) {
    		 
    		$form->bind($this->get('request'));
    		 
    		if ( $form->isValid() ) {
    			
    			$data = $form->getData();
    			 
    			$mailTemplate->setContent($data->getContent());
    			$mailTemplate->setSubject($data->getSubject());
    			$mailTemplate->setMailType($data->getMailType());
    			
    			$this->getDoctrine()->getEntityManager()->persist($mailTemplate);
    			$this->getDoctrine()->getEntityManager()->flush();
    			 
    			return $this->redirect($this->generateUrl("mail"));
    		}
    	}
    	
    	return array('form'=>$form->createView());
    }
    
    /**
     * Charger les type de mail
     * @Route("/loadTypeMails", name="loadTypeMails")
     * @Template()
     */
    public function loadTypeMailsAction () {
    
    	$em = $this->getDoctrine()->getEntityManager();
    	$currentCity = $this->get('session_service')->getCity();
    
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    
    	$page = $this->getRequest()->get("page",1);
    	$entities = $this->get("mail_service")->getTypeMails();
    
    	//pagination
    	$nb_entities = count($entities);
    	$nb_entities_page = Constants::mailstype_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$entities = array_slice($entities, $offset,$nb_entities_page);
    
    	return  array (
    			'entities' => $entities,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    }
    
    /**
     * ajouter un  nouveau type de mail
     * @Route("/addTypeMail",name="addType_mail")
     * @Template()
     */
    public function addTypeMailAction () {
    	$em = $this->getDoctrine()->getEntityManager();
    	$form = $this->createForm(new MailTypeType(),new MailType());
    	// On vérifie qu'elle est de type POST
    	if ($this->getRequest()->getMethod() == 'POST') {
    		// On fait le lien Requête <-> Formulaire
    		$form->bind($this->getRequest());
    		// On vérifie que les valeurs rentrées sont correctes
    		if ($form->isValid()) {
    			// On enregistre notre objet $mailType dans la base de données
    			$mailType = $form->getData();
    			$em->persist($mailType);
    			$em->flush();
    			// on redirige vers la liste des types de mails
    			return $this->redirect($this->generateUrl("loadTypeMails"));
    		}
    	}
    
    		
    	return array('form'=>$form->createView());
    		
    }
    
    


/**
 * modifier un type de mail
 * @Route("/edit/{id}",name="editType_mail")
 * @Template()
 */
  public function editTypeMailAction (MailType $mailType) {
	$form = $this->createForm(new MailTypeType, $mailType);
	$request=$this->getRequest();
	// On vérifie qu'elle est de type POST
	if ($this->getRequest()->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		$form->bind($this->getRequest());
		// On vérifie que les valeurs rentrées sont correctes
		if ($form->isValid()) {
			// On enregistre notre objet $mailType dans la base de données
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($mailType);
			$em->flush();
			// on redirige vers les types de place
			return $this->redirect($this->generateUrl("loadTypeMails"));
		}
			
	}

		
	return array(
			'mailType'=> $mailType,
			'form'=>$form->createView());
		
}
/**
 * Deletes a MailType entity.
 *
 * @Route("/{id}/deleteType", name="mailType_delete" ) //requirements={"id" = "\d+"}
 * @Method("POST")
 * @Template()
 */
public function deleteMailTypeAction($id) {

	$em = $this->getDoctrine()->getManager();
	$entity = $em->getRepository('CTRVMailBundle:MailType')->find($id);

	$em->remove($entity);
	$em->flush();

	return new Response(json_encode(array('result'=>true)));
}
}
