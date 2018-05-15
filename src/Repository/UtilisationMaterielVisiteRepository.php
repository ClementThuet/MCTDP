<?php

namespace App\Repository;

use App\Entity\UtilisationMaterielVisite;
use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Visite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visite[]    findAll()
 * @method Visite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisationMaterielVisiteRepository extends EntityRepository
{
   /* public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Visite::class);
    }*/
    
    
    
    public function findByVisite($idVisite)
    {
        return $this->createQueryBuilder('umv')
             ->innerJoin('umv.visite', 'vis')
            ->where('vis.id = :idVisite ')
            ->setParameters([
            'idVisite' => $idVisite,
        ]);
    }
    
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
}
