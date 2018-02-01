<?php

namespace Cymo\Bundle\EntityRatingBundle\Event;

use Cymo\Bundle\EntityRatingBundle\Entity\EntityRate;
use Cymo\Bundle\EntityRatingBundle\Entity\EntityRateInterface;
use Symfony\Component\EventDispatcher\Event;

class RateCreatedEvent extends Event
{
    const NAME = 'cymo.entity_rating.rate_created';
    /**
     * @var EntityRate
     */
    private $entityRate;

    public function __construct(EntityRateInterface $entityRate)
    {
        $this->entityRate = $entityRate;
    }

    /**
     * @return EntityRateInterface
     */
    public function getEntityRate(): EntityRateInterface
    {
        return $this->entityRate;
    }

}