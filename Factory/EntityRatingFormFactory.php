<?php
/**
 * Created by PhpStorm.
 * User: cymo
 * Date: 25/01/18
 * Time: 16:42
 */

namespace Cymo\Bundle\EntityRatingBundle\Factory;

use Cymo\Bundle\EntityRatingBundle\Annotation\Rated;
use Cymo\Bundle\EntityRatingBundle\Form\RatingType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactory;

class EntityRatingFormFactory
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getForm(Rated $annotation, $entityType, $entityId)
    {
        return $this->formFactory->createNamedBuilder('entityrating', FormType::class)
            ->add(
                'rate',
                RatingType::class,
                [
                    'choices' => $this->getChoices($annotation),
                ]
            )
            ->add('entityType', HiddenType::class, ['data' => $entityType])
            ->add('entityId', HiddenType::class, ['data' => $entityId])
            ->getForm();
    }

    protected function getChoices(Rated $annotation)
    {
        $choices = [];

        for ($i = $annotation->getMin(); $i <= $annotation->getMax(); $i += $annotation->getStep()) {
            $choices["$i"] = "$i";
        }

        return array_reverse(array_flip($choices));
    }
}