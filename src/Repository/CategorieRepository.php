<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class CategorieRepository extends EntityRepository
{
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('categorie')
                 ->orderBy('categorie.id', 'ASC');
    }
    public function findByNameASC()
    {
        return $this->createQueryBuilder('categorie')
                 ->orderBy('categorie.nom', 'ASC');
    }
}