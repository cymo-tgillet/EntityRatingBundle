<?php

namespace Cymo\Bundle\EntityRatingBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *   @Attribute("minRating", type = "integer"),
 *   @Attribute("maxRating", type = "integer"),
 *   @Attribute("ratingStep", type = "mixed"),
 * })
 */
final class RatingActivated implements Annotation
{
    private $minRating;
    private $maxRating;
    private $ratingStep;

    public function __construct(array $values)
    {
        $this->minRating  = $values['minRating'];
        $this->maxRating  = $values['maxRating'];
        $this->ratingStep = $values['ratingStep'];
    }

    /**
     * @return mixed
     */
    public function getMinRating()
    {
        return $this->minRating;
    }

    /**
     * @return mixed
     */
    public function getMaxRating()
    {
        return $this->maxRating;
    }

    /**
     * @return mixed
     */
    public function getRatingStep()
    {
        return $this->ratingStep;
    }
}
