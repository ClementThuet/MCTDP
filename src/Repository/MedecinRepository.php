<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class MedecinRepository extends EntityRepository
{
   public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('medecin')
                 ->orderBy('medecin.nom', 'ASC');
    }
}