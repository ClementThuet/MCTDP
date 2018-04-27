<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class ProduitRepository extends EntityRepository
{
   public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('produit')
                 ->orderBy('produit.nom', 'ASC');
    }
}