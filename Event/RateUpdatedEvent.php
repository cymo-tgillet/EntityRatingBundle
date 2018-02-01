<?php

namespace Cymo\Bundle\EntityRatingBundle\Event;

use Cymo\Bundle\EntityRatingBundle\Entity\EntityRate;
use Symfony\Component\EventDispatcher\Event;

class RateUpdatedEvent extends Event
{
    const NAME = 'cymo.entity_rating.rate_updated';
    /**
     * @var EntityRate
     */
    private $entityRate;

    public function __construct(EntityRate $entityRate)
    {
        $this->entityRate = $entityRate;
    }

    /**
     * @return EntityRate
     */
    public function getEntityRate(): EntityRate
    {
        return $this->entityRate;
    }

}