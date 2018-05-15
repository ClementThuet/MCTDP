<?php

namespace App\Repository;

use App\Entity\Visite;
use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Visite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visite[]    findAll()
 * @method Visite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisiteRepository extends EntityRepository
{
   /* public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Visite::class);
    }*/
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('visite')
                 ->orderBy('visite.date', 'DESC');
    }
    public function findVisitesParMois($valeur,$mois)
    {
        return $this->createQueryBuilder('v')
            ->where('YEAR(v.date) = :annee ')
            ->andwhere('MONTH(v.date) = :mois ')
            ->setParameters([
            'mois' => $mois,
            'annee' => $valeur,
        ]);
    }
    
    public function findVisitesParJour($annee,$mois,$jour)
    {
        return $this->createQueryBuilder('v')
            ->where('YEAR(v.date) = :annee ')
            ->andwhere('MONTH(v.date) = :mois ')
            ->andwhere('DAY(v.date) = :jour ')
            ->setParameters([
            'jour' => $jour,
            'mois' => $mois,
            'annee'=>$annee,
        ]);
    }
    
    public function findByDate($date)
    {
        return $this->createQueryBuilder('v')
            ->where('DATE(v.date) = :date ')
            ->setParameters([
            'date' => $date,
        ]);
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
