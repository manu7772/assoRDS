<?php

namespace AcmeGroup\LaboBundle\Entity;

// use AcmeGroup\LaboBundle\Entity\baseRepository;
use labo\Bundle\TestmanuBundle\Entity\evenementRepository as evenementBaseRepo;

/**
 * evenementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class evenementRepository extends evenementBaseRepo {

	/**
	 * findNewshome
	 * Renvoie la news en page d'accueil -> évènement de type edito le plus récent
	 * @return AcmeGroup\LaboBundle\Entity\evenement
	 */
	public function findNewshome($date = null) {
		if($date === null) $date = new \Datetime();
		$qb = $this->createQueryBuilder('element');
		$qb->join('element.typeEvenement', 'te')
			->where($qb->expr()->in('te.slug', $this->defineTypeEvents('edito')))
			->andWhere('element.datedebut <= :date')
			->setParameter('date', $date)
			->orderBy('element.datedebut', 'DESC')
			->leftJoin('element.image', 'im')
			->addSelect('im')
			->setMaxResults(1); // un seul résultat
		$qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		$r = $qb->getQuery()->getResult();
		if(count($r) < 1) $r = null; else $r = $r[0];
		return $r;
	}


}