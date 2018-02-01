<?php

namespace Cymo\Bundle\EntityRatingBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EntityRateRepository extends EntityRepository implements EntityRateRepositoryInterface
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

    public function getRateByIpAndUserAgent($ip, $userAgent, $entityId, $entityType)
    {
        return $this->createQueryBuilder('er')
            ->where('er.entityType = :entity_type')
            ->setParameter('entity_type', $entityType)
            ->andWhere('er.entityId = :entity_id')
            ->setParameter('entity_id', $entityId)
            ->andWhere('er.userAgent = :user_agent')
            ->setParameter('user_agent', $userAgent)
            ->andWhere('er.ip = :ip')
            ->setParameter('ip', $ip)
            ->getQuery()
            ->getOneOrNullResult();
    }

}