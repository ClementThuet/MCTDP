<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class FournisseurRepository extends EntityRepository
{
   public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('fournisseur')
                 ->orderBy('fournisseur.nom', 'ASC');
    }
}