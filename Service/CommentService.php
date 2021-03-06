<?php

namespace CTRV\CommonBundle\Service;

use CTRV\CommonBundle\Entity\Comment;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommentService {
	
	protected $mailer;
	protected $doctrine;
	protected $em;
	protected $service_container;
	protected $templating;
	
	
	public function __construct(\Swift_Mailer $mailer,RegistryInterface $doctrine, ContainerInterface $service_container, TwigEngine $templating,TranslatorInterface $translator) {
		
		$this->mailer = $mailer;
		$this->doctrine = $doctrine;
		$this->em = $doctrine->getEntityManager();
		$this->service_container = $service_container;
		$this->templating = $templating;
		$this->translator = $translator;
	}
	
	/**
	 * Retourne les commentaires des agendas de la ville spécifié
	 */
	public function getAgendaComment ($currentCity) {
		
		$entities = $this->em->getRepository('CTRVCommonBundle:Comment')
		->getAgendaComment($this->em->getRepository('CTRVCommonBundle:City')->find($currentCity->getId()));
		return $entities;
	}
	
	/**
	 * Retourne les commentaires des événements de la ville spécifié
	 */
	public function getEventComment ($currentCity) {
		
		$entities = $this->em->getRepository('CTRVCommonBundle:Comment')
		->getEventComment($this->em->getRepository('CTRVCommonBundle:City')->find($currentCity->getId()));
		return $entities;
	}
	
	/**
	 * Retourne les commentaires des places de la ville spécifié
	 */
	public function getPlaceComment ($currentCity) {
		
		$entities = $this->em->getRepository('CTRVCommonBundle:Comment')
		->getPlaceComment($this->em->getRepository('CTRVCommonBundle:City')->find($currentCity->getId()));
		return $entities;
	}
	
	/**
	 * retourne la liste des abus sur les commentaires
	 * @return unknown
	 */
	public function getEventCommentAbuse($currentCity) {
		$entities = $this->em->getRepository('CTRVCommonBundle:Comment')
		->getEventCommentAbuse($this->em->getRepository('CTRVCommonBundle:City')->find($currentCity->getId()));
		return $entities;
	}
	/**
	 * retourne la liste des abus sur les commentaires des places
	 * @return unknown
	 */
	public function getPlaceCommentAbuse($currentCity) {
		$entities = $this->em->getRepository('CTRVCommonBundle:Comment')
		->getPlaceCommentAbuse($this->em->getRepository('CTRVCommonBundle:City')->find($currentCity->getId()));
		return $entities;
	}
	
	
}
