<?php

namespace Cymo\Bundle\EntityRatingBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *   @Attribute("maxRating", type = "integer"),
 * })
 */
final class RatingActivated implements Annotation
{
    public function __construct(array $values)
    {
        $this->maxRating = $values['maxRating'];
    }
}
