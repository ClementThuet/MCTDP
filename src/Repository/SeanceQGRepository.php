<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class SeanceQGRepository extends EntityRepository
{
   public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('seanceQG')
                 ->orderBy('seanceQG.date', 'DESC');
    }
    public function findSeancesPatientQueryBuilder($idPatient)
    {
        return $this->createQueryBuilder('seanceQG')
                ->innerJoin('seanceQG.patient', 'pat', 'WITH', 'pat.id = :valeur')
                ->setParameter('valeur', ''.$idPatient.'')
                ->orderBy('seanceQG.date', 'DESC');
    }
     /*->from('App\Entity\Produit', 'p')   
                ->innerJoin('p.categorie', 'cat', 'WITH', 'cat.id = :valeur')
                ->setParameter('valeur', ''.$ids.'');*/
}