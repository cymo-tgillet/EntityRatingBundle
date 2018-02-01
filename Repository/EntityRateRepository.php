<?php

namespace Cymo\Bundle\EntityRatingBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EntityRateRepository extends EntityRepository
{
    public function getEntityAverageRate($entityId, $entityType)
    {
        return $this->createQueryBuilder('er')
            ->select('avg(er.rate) as average_rate, count(er.id) as rate_count')
            ->where('er.entityType = :entity_type')
            ->setParameter('entity_type', $entityType)
            ->andWhere('er.entityId = :entity_id')
            ->setParameter('entity_id', $entityId)
            ->getQuery()
            ->getSingleResult();
    }
}