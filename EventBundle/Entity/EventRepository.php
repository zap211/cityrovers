<?php

namespace CTRV\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Mapping as ORM;


class EventRepository extends EntityRepository
{
/**
 * retourne la liste des événements en cours de la ville courante
 */	
	public function getEvent($city, $first, $last) {
		$qb = $this->createQueryBuilder("e")
		->where("e.auteur is not null")
		->andWhere("e.city=?1")
		->andWhere("e.isPrivate=?2")
		->andWhere("e.duration > 0")
		->andWhere("e.isRealtime=?3")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, true)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * retourne le nombre d'événements en cours de la ville courante
	 */
	public function getEventNumber($city) {
		$qb = $this->createQueryBuilder("e")
		->select("count(e)")
		->where("e.auteur is not null")
		->andWhere("e.duration > 0")
		->andWhere("e.city=?1")
		->andWhere("e.isPrivate=?2")
		->andWhere("e.isRealtime=?3")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, true)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	
	/**
	 * retourne la liste des événements ayant des mis à jour proposés de la ville courante
	 */
	public function getEventUpdatedByCity($city, $first, $last) {
		$qb = $this->createQueryBuilder("e")
		->from('CTRV\EventBundle\Entity\UpdatedEvent','u')
		->where("e.id=u.event")
		->andWhere("e.auteur is not null")
		->andWhere("e.city=?1")
		->andWhere("e.isPrivate=?2")
		->andWhere("e.duration > 0")
		->andWhere("e.isRealtime=?3")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, true)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}
	/**
	 * retourne la liste des événements ayant des mis à jour proposés de la ville courante
	 */
	public function getEventWithUpdateProposedByCity($city) {
		$qb = $this->createQueryBuilder("e")
		->from('CTRV\EventBundle\Entity\UpdatedEvent','u')
		->where("e.id=u.event")
		->andWhere("e.auteur is not null")
		->andWhere("e.city=?1")
		->andWhere("e.isPrivate=?2")
		->andWhere("e.duration > 0")
		->andWhere("e.isRealtime=?3")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, true)
		;
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * retourne un événement ayant des mis à jour proposés de la ville courante
	 */
	public function getcurrentEventWithUpdateProposed($id, $city, $first, $last) {
		$qb = $this->createQueryBuilder("e")
		->from('CTRV\EventBundle\Entity\UpdatedEvent','u')
		->where("e.id=u.event")
		->andWhere("e.auteur is not null")
		->andWhere("e.id=?1")
		->andWhere("e.city=?2")
		->andWhere("e.isPrivate=?3")
		->andWhere("e.duration > 0")
		->andWhere("e.isRealtime=?4")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $id)
		->setParameter(2, $city)
		->setParameter(3, false)
		->setParameter(4, true)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}
	
	
	/**
	 * retourne le nombre d'événements ayant des mis à jour proposés de la ville courante
	 */
	public function getEventUpdatedByCityNumber($city) {
		$qb = $this->createQueryBuilder("e")
		->select("count(DISTINCT u.event)")
		->from('CTRV\EventBundle\Entity\UpdatedEvent','u')
		->where("e.id=u.event")
	    ->andWhere("e.auteur is not null")
		->andWhere("e.city=?1")
		->andWhere("e.isPrivate=?2")
		->andWhere("e.duration > 0")
		->andWhere("e.isRealtime=?3")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, true)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	
	/**
	 * retourne la liste des événements passés de la ville courante
	 */
	public function getEventPassed($city, $first, $last) {
		$qb = $this->createQueryBuilder("p")
		->where("p.auteur is not null")
		->andWhere("p.city=?1")
		->andWhere("p.isPrivate=?2")
		->andWhere("p.duration <= 0")
		->andWhere("p.isRealtime=?3")
		->orderBy('p.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, true)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * retourne le nombre d'événements passés de la ville courante
	 */
	public function getEventPassedNumber($city) {
		$qb = $this->createQueryBuilder("p")
		->select("count(p)")
		->where("p.auteur is not null")
		->andWhere("p.duration <= 0")
		->andWhere("p.city=?1")
		->andWhere("p.isPrivate=?2")
		->andWhere("p.isRealtime=?3")
		->orderBy('p.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, true)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	
	/**
	 * retourne la liste des agendas passés de la ville courante
	 */	
	public function getPassedAgenda ($city, $first, $last, $date) {
		$qb = $this->createQueryBuilder("p")
		->where("p.auteur is not null")
		->andWhere("p.city=?1")
		->andWhere("p.isPrivate=?2")
		->andWhere("p.isRealtime=?3")
		->andWhere("p.startDate is not null ")
		->andWhere("p.startDate<?4")
		->orderBy('p.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, false)
		->setParameter(4, $date)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * retourne le nombre d'agendas en cours de la ville courante
	 */
	public function getPassedAgendaNumber($city, $date) {
		$qb = $this->createQueryBuilder("p")
		->select("count(p)")
		->where("p.auteur is not null")
		->andWhere("p.city=?1")
		->andWhere("p.isPrivate=?2")
		->andWhere("p.isRealtime=?3")
		->andWhere("p.startDate is not null ")
		->andWhere("p.startDate<?4")
		->orderBy('p.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, false)
		->setParameter(4, $date)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * retourne la liste des agendas en cours de la ville courante
	 */
	public function getCurrentAgenda ($city, $first, $last, $date) {
		$qb = $this->createQueryBuilder("p")
		->where("p.auteur is not null")
		->andWhere("p.city=?1")
		->andWhere("p.isPrivate=?2")
		->andWhere("p.isRealtime=?3")
		->andWhere("p.startDate is not null ")
		->andWhere("p.startDate >=?4")
		->orderBy('p.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, false)
		->setParameter(4, $date)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * retourne le nombre d'agendas en cours de la ville courante
	 */
	public function getCurrentAgendaNumber($city, $date) {
		$qb = $this->createQueryBuilder("p")
		->select("count(p)")
		->where("p.auteur is not null")
		->andWhere("p.city=?1")
		->andWhere("p.isPrivate=?2")
		->andWhere("p.isRealtime=?3")
		->andWhere("p.startDate is not null ")
		->andWhere("p.startDate >=?4")
		->orderBy('p.addedDate', 'DESC')
		->setParameter(1, $city)
		->setParameter(2, false)
		->setParameter(3, false)
		->setParameter(4, $date)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * retourne la liste des mis à jour proposés pour l'événement  spécifié
	 */
	public function getUpdatePerEvent ($id, $city, $first, $last) {
		$qb = $this->createQueryBuilder("e")
		->select('e')
		->from('CTRV\EventBundle\Entity\UpdatedEvent','u')
		->where("e.id=?1")
		->andWhere("e.id=u.event")
		->andWhere("e.city=?2")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1,$id)
		->setParameter(2,$city)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	
	/**
	 * retourne le nombre de mis à jour proposés pour l'événement  spécifié
	 */
	public function getUpdatePerEventNumber ($id) {
		$qb = $this->createQueryBuilder("e")
		->select("count(e)")
		->from('CTRV\EventBundle\Entity\UpdatedEvent','u')
		->where("e.id=?1")
		->andWhere("e.id=u.event")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1,$id)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
}