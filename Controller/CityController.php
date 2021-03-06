<?php

namespace CTRV\CommonBundle\Controller;

use Doctrine\ORM\EntityRepository;
use CTRV\CommonBundle\Form\CityType;
use CTRV\CommonBundle\Entity\City;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use CTRV\CommonBundle\DependencyInjection\Constants;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * City controller.
 *
 * @Route("/city")
 */
class CityController extends Controller
{
    
    /**
     * @Route("/list",name="city")
     * @Template()
     * Retourne la liste de toutes les villes
     */
    public function getAllCitiesAction(){
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$currentCity = $this->get('session_service')->getCity();
    	
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    	$page = intval($this->getRequest()->get("page",1));
    	
    	//pagination
    	$nb_entities = $cities = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->getAllCitiesNumber();
    	$nb_entities_page = Constants::city_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    	 
    	$cities = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->getAllCities($offset, $nb_entities_page);
    	
    	return  array (
    			'entities' => $cities,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    } 
    
    /**
     * ajouter une nouvelle ville
     * @Route("/add",name="addCity")
     * @Template()
     */
    public function addCityAction () {
    	$em = $this->getDoctrine()->getEntityManager();
    	$form = $this->createForm(new CityType(),new City());
    	// On vérifie qu'elle est de type POST
    	if ($this->getRequest()->getMethod() == 'POST') {
    		// On fait le lien Requête <-> Formulaire
    		$form->bind($this->getRequest());
    		// On vérifie que les valeurs rentrées sont correctes
    		if ($form->isValid()) {
    			// On enregistre notre objet $city dans la base de données
    			$city = $form->getData();
    			$em->persist($city);
    			$em->flush();
    			// on redirige vers la liste des types de place
    			return $this->redirect($this->generateUrl("city"));
    		}
    	}
    		return array('form'=>$form->createView());
    		
    }
    
    /**
     * modifier une ville
     * @Route("/editCity/{id}",name="edit_city")
     * @Template()
     */
    public function editCityAction(City $city) {
    	$form = $this->createForm(new CityType(), $city);
    	$request=$this->getRequest();
    	// On vérifie qu'elle est de type POST
    	if ($this->getRequest()->getMethod() == 'POST') {
    		// On fait le lien Requête <-> Formulaire
    		$form->bind($this->getRequest());
    		// On vérifie que les valeurs rentrées sont correctes
    		if ($form->isValid()) {
    			// On enregistre notre objet $city dans la base de données
    			$em = $this->getDoctrine()->getEntityManager();
    			$em->persist($city);
    			$em->flush();
    			// on redirige vers la liste des places existantes
    			return $this->redirect($this->generateUrl("city"));
    		}
    
    	}
    
    		
    	return array(
    			'city'=> $city,
    			'form'=>$form->createView());
    		
    }
    /**
     * Deletes a City entity.
     *
     * @Route("/{id}/delete", name="city_delete" ) //requirements={"id" = "\d+"}
     * @Method("POST")
     * @Template()
     */
    public function deleteAction($id) {
    
    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVCommonBundle:City')->find($id);
    
    	$em->remove($entity);
    	$em->flush();
    
    	return new Response(json_encode(array('result'=>true)));
    }
    
    
     
}
