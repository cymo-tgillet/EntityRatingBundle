<?php

namespace Cymo\Bundle\EntityRatingBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *   @Attribute("min", type = "integer"),
 *   @Attribute("max", type = "integer"),
 *   @Attribute("step", type = "mixed"),
 * })
 */
final class Rated implements Annotation
{
    private $min;
    private $max;
    private $step;

    public function __construct(array $values)
    {
        $this->min  = $values['min'];
        $this->max  = $values['max'];
        $this->step = $values['step'];
    }

    /**
     * @return mixed
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @return mixed
     */
    public function getStep()
    {
        return $this->step;
    }
}
