<?php

namespace Cymo\Bundle\EntityRatingBundle\Manager;

use Cymo\Bundle\EntityRatingBundle\Annotation\RatingActivated;
use Cymo\Bundle\EntityRatingBundle\Exception\UnsupportedEntityRatingClass;
use Cymo\Bundle\EntityRatingBundle\Factory\EntityRatingFormFactory;
use Doctrine\Common\Annotations\AnnotationReader;

class EntityRatingManager
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;
    /**
     * @var EntityRatingFormFactory
     */
    private $formFactory;

    public function __construct(AnnotationReader $annotationReader, EntityRatingFormFactory $formFactory)
    {
        $this->annotationReader = $annotationReader;
        $this->formFactory      = $formFactory;
    }

    public function generateForm($class)
    {
        if ($annotation = $this->typeIsSupported($class)) {
            return $this->formFactory->getForm($class, $annotation);
        }
        throw new UnsupportedEntityRatingClass(sprintf('Class does not support EntityRating, you must add the `RatingActivated` annotation to the %s class first', $class));
    }

    /**
     * @param $entityClass
     *
     * @return bool|RatingActivated
     */
    protected function typeIsSupported($entityClass)
    {
        $reflClass        = new \ReflectionClass($entityClass);
        $classAnnotations = $this->annotationReader->getClassAnnotations($reflClass);

        foreach ($classAnnotations AS $annot) {
            if ($annot instanceof RatingActivated) {
                return $annot;
            }
        }

        return false;
    }
}