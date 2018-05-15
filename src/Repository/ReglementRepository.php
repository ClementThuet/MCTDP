<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class ReglementRepository extends EntityRepository
{
   public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('reglement')
                 ->orderBy('reglement.date', 'ASC');
    }
    public function findYearByMonth($origine,$valeur,$mois)
    {
        return $this->createQueryBuilder('r')
            ->where('YEAR(r.date) = :annee ')
            ->andwhere('MONTH(r.date) = :mois ')
            ->andwhere('r.origine LIKE :origine  ')
            ->setParameters([
            'origine'=>'%'.$origine.'%',
            'mois' => $mois,
            'annee' => $valeur,
        ]);
    }
    public function findMonthByDay($origine,$annee,$valeur,$jour)
    {
        return $this->createQueryBuilder('r')
            ->where('YEAR(r.date) = :annee ')
            ->andwhere('MONTH(r.date) = :mois ')
            ->andwhere('DAY(r.date) = :jour ')
            ->andwhere('r.origine LIKE :origine  ')
            ->setParameters([
            'origine'=>'%'.$origine.'%',
            'jour' => $jour,
            'mois' => $valeur,
            'annee'=>$annee,
        ]);
    }
    
    public function findByDate($origine,$date)
    {
        return $this->createQueryBuilder('r')
            ->where('DATE(r.date) = :date ')
            ->andwhere('r.origine LIKE :origine  ')
            ->setParameters([
            'origine'=>'%'.$origine.'%',
            'date' => $date,
        ]);
    }
    
    public function findByMDR($mdr)
    {
        return $this->createQueryBuilder('r')
            ->where('r.modeReglement = :mdr ')
            ->setParameters([
            'mdr' => $mdr,
        ]);
    }
}