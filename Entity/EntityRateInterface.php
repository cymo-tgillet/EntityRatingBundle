<?php

namespace Cymo\Bundle\EntityRatingBundle\Entity;

interface EntityRateInterface
{
    public function setRate(float $rate);

    public function setEntityType(string $entityType);

    public function setIp(string $ip);

    public function setUserAgent(string $userAgent);

    public function setCreatedAt(\DateTime $createdAt);

    public function setUpdatedAt(\DateTime $updatedAt);

    public function setEntityId(int $entityId);
}
