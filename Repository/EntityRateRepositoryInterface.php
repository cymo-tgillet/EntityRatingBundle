<?php

namespace Cymo\Bundle\EntityRatingBundle\Repository;

Interface EntityRateRepositoryInterface
{
    public function getEntityAverageRate($entityId, $entityType);

    public function getRateByIpAndUserAgent($ip, $userAgent, $entityId, $entityType);
}