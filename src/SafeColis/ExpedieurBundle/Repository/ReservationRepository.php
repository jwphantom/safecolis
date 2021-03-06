<?php

namespace SafeColis\ExpedieurBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends \Doctrine\ORM\EntityRepository
{

    public function searchVoyageur($id)
    {
  
        $query = $this->_em->createQuery(
            'SELECT a 
            FROM SafeColisExpedieurBundle:Reservation a
            WHERE b.user = :id
            AND b.id = a.voyage');
              $query->setParameter('id', $id);

        $results = $query->getResult();
      
        return $results;

           
    }
    public function findVoyage($id,$depart,$arrive)
    {
  
        $query = $this->_em->createQuery(
            'SELECT v
            FROM SafeColisVoyageurBundle:Voyageur v
            WHERE v.user != :id
            AND v.villedepart = :depart
            AND v.villearrive = :arrive
            AND v.kilodisponible > 0
            AND v.active = :active'
            );
              $query->setParameter('id', $id);
              $query->setParameter('depart', $depart);
              $query->setParameter('arrive', $arrive);
              $query->setParameter('active', 1);




        $results = $query->getResult();
      
        return $results;

           
    }

    public function findVoyageWithDate($id,$depart,$arrive,$date)
    {
  
        $query = $this->_em->createQuery(
            'SELECT v
            FROM SafeColisVoyageurBundle:Voyageur v
            WHERE v.user != :id
            AND v.villedepart = :depart
            AND v.villearrive = :arrive
            AND v.datedepart = :date_depart
            AND v.kilodisponible > 0
            '
            );
              $query->setParameter('id', $id);
              $query->setParameter('depart', $depart);
              $query->setParameter('arrive', $arrive);
              $query->setParameter('date_depart', $date);




        $results = $query->getResult();
      
        return $results;

           
    }
}
