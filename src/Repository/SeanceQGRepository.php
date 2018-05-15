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
    public function findSeancesCouponPatientQueryBuilder($idPatient,$idCOuponQG)
    {
        return $this->createQueryBuilder('seanceQG')
                ->innerJoin('seanceQG.patient', 'pat', 'WITH', 'pat.id = :valeur')
                ->innerJoin('seanceQG.couponQiGong', 'cqg', 'WITH', 'cqg.id = :idCouponQG')
                ->setParameter('valeur', ''.$idPatient.'')
                ->setParameter('idCouponQG', ''.$idCOuponQG.'')
                ->orderBy('seanceQG.date', 'DESC');
    }
     public function findSeancesPatientQueryBuilder($idPatient)
    {
        return $this->createQueryBuilder('seanceQG')
                ->innerJoin('seanceQG.patient', 'pat', 'WITH', 'pat.id = :valeur')
                ->setParameter('valeur', ''.$idPatient.'')
                ->orderBy('seanceQG.date', 'DESC');
    }
}