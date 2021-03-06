<?php

namespace CTRV\FlowBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use CTRV\CommonBundle\DependencyInjection\Constants;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CTRV\FlowBundle\Entity\GroupUser;


/**
 * Groupe controller.
 *
 * @Route("/groupe")
 */
class GroupeController extends Controller
{
	
	/**
     * Charge les donnees de tous les groupes 
     * @Route("/list", name="groupe")
     * @Template()
     */
    public function loadGroupAction () {
    	
    	$currentCity = $this->get('session_service')->getCity();
    	$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    	
    	$page = intval ($this->getRequest()->get("page",1));
    	//recupére l'ensemble des groupes existants
    	$groups = $this->getDoctrine()->getRepository('CTRVFlowBundle:GroupUser')->getGroup();
    	//recupére l'ensemble des groupes de la ville courante
    	$groups_by_city = $this->getDoctrine()->getRepository('CTRVFlowBundle:GroupUser')->getGroupByCity($city);
    	// on crée un tableau
    	$tab = array();
    	// On récupére le nombre de membres de chaque groupe pour le placer dans le tableau
    	foreach ($groups_by_city as $entity) {
    		$tab[$entity->getId()] = $this->getDoctrine()->getRepository('CTRVFlowBundle:GroupUser')->getGroupMemberNumber($entity);
    	}
    	
    	  //pagination
        $group_number = $this->getDoctrine()->getRepository('CTRVFlowBundle:GroupUser')->getGroupNumber();
        $nb_entities1 = $this->getDoctrine()->getRepository('CTRVFlowBundle:GroupUser')->getGroupByCityNumber($city);
        $nb_entities_page = Constants::groupes_number_per_page;
        $nb_pages = ceil($nb_entities1/$nb_entities_page);
        $offset = ($page-1) * $nb_entities_page;
        
        $groups_by_city = array_slice($groups_by_city, $offset,$nb_entities_page);
       	
       	return  array (
          'group_member'=>$tab,
            'entities' => $groups,
       			'entities1' => $groups_by_city,
         'nb_pages' => $nb_pages,
         'page' => $page,
         'nb_entities' => $group_number,
       			'nb_entities1' => $nb_entities1,
         'city'=>$currentCity
        );
    }  
    
    /**
     * Bloquer un groupe
     *
     * @Route("/block/{id}", name="groupe_block" ) //requirements={"id" = "\d+"}
     * @Template()
     */
    public function blockAction ($id) {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVFlowBundle:GroupUser')->find($id);
    	$entity->setIsBlocked("1");
    	$em->persist($entity);
    	$em->flush();
    
    	return $this->redirect($this->generateUrl("groupe"));
    }
    /**
     * Debloquer un groupe
     *
     * @Route("/deblock/{id}", name="groupe_deblock" ) //requirements={"id" = "\d+"}
     * @Template()
     */
    public function deblockAction ($id) {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVFlowBundle:GroupUser')->find($id);
    	$entity->setIsBlocked("0");
    	$em->persist($entity);
    	$em->flush();
    
    	return $this->redirect($this->generateUrl("groupe"));
    }
    /**
     * Deletes a groupe entity.
     *
     * @Route("/{id}/delete", name="groupe_delete" ) //requirements={"id" = "\d+"}
     * @Method("POST")
     * @Template()
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CTRVFlowBundle:GroupUser')->find($id);

        $em->remove($entity);
        $em->flush();
        
        return new Response(json_encode(array('result'=>true)));
    }
}
