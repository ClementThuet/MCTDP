<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;


class PrescriptionRepository extends EntityRepository
{
   /* public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Visite::class);
    }*/
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('prescription')
                 ->orderBy('prescription.date', 'DESC');
    }
    public function findPrescriptionsPatientQueryBuilder($idPatient)
    {
        return $this->createQueryBuilder('prescription')
                ->innerJoin('prescription.visite', 'visit')
                ->innerJoin('visit.patient', 'pat')
                ->where('pat.id = :valeur')
                ->setParameter('valeur', ''.$idPatient.'')
                ->orderBy('prescription.date', 'DESC');
    }
    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('v')
            ->where('v.something = :value')->setParameter('value', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
