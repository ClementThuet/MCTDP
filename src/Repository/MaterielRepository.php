<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class MaterielRepository extends EntityRepository
{
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('materiel')
                 ->orderBy('materiel.nom', 'ASC');
    }
    
}