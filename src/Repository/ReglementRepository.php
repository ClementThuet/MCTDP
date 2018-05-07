<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class ReglementRepository extends EntityRepository
{
   public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('reglement')
                 ->orderBy('produit.nom', 'ASC');
    }
    public function findYearByMonth($valeur,$mois)
    {
        return $this->createQueryBuilder('r')
            ->where('YEAR(r.date) = :annee ')
            ->andwhere('MONTH(r.date) = :mois ')
            ->setParameters([
            'mois' => $mois,
            'annee' => $valeur,
        ]);
    }
    public function findMonthByDay($annee,$valeur,$jour)
    {
        return $this->createQueryBuilder('r')
            ->where('YEAR(r.date) = :annee ')
            ->andwhere('MONTH(r.date) = :mois ')
            ->andwhere('DAY(r.date) = :jour ')
            ->setParameters([
            'jour' => $jour,
            'mois' => $valeur,
            'annee'=>$annee,
        ]);
    }
    
    public function findByDate($date)
    {
        return $this->createQueryBuilder('r')
            ->where('DATE(r.date) = :date ')
            ->setParameters([
            'date' => $date,
        ]);
    }
}