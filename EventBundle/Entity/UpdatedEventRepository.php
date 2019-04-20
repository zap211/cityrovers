<?php

namespace CTRV\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Mapping as ORM;
/**
 * UpdatedEventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UpdatedEventRepository extends EntityRepository
{
	/**
	 * Retourne la liste des mis à jour proposés pour un événement spécifié
	 */
	public function getUpdatePerEvent($id ,$city, $first, $last) {
		$qb = $this->createQueryBuilder("e")
		->from('CTRV\EventBundle\Entity\Event','ev')
		->where("e.auteur is not null")
		->andWhere("ev.id=e.event")
		->andWhere("e.event=?1")
		->andWhere("ev.city=?2")
		->andWhere("e.duration > 0")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $id)
		->setParameter(2,$city)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Retourne le nombre de mis à jour proposés pour un événement spécifié
	 */
	public function getUpdatePerEventNumber($id, $city) {
		$qb = $this->createQueryBuilder("e")
		->select("count(e)")
		->from('CTRV\EventBundle\Entity\Event','ev')
		->where("e.auteur is not null")
		->andWhere("ev.id=e.event")
		->andWhere("e.event=?1")
		->andWhere("ev.city=?2")
		->andWhere("e.duration > 0")
		->orderBy('e.addedDate', 'DESC')
		->setParameter(1, $id)
		->setParameter(2,$city)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
}