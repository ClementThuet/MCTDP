<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class CouponQiGongRepository extends EntityRepository
{
   public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('couponQiGong')
                 ->orderBy('couponQiGong.id', 'ASC');
    }
}