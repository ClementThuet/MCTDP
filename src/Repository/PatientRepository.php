<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PatientRepository extends EntityRepository
{
  public function getOrderQueryBuilder()
  {
    return $this->createQueryBuilder('patient')
            ->orderBy('patient.nom', 'ASC');
  }
}




