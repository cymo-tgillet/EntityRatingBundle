<?php

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

    public function getForm(Rated $annotation, $entityType, $entityId, $formName = null)
    {
        return $this->formFactory->createNamedBuilder(
            $formName ?? 'entityrating_'.uniqid(),
            FormType::class,
            null,
            [
                'attr' => [
                    'class'                 => 'entityrating-form',
                    'data-entity-rating-id' => 'entityrating-form-'.$entityType.'-'.$entityId,
                ],
            ]
        )
            ->add(
                'rate',
                RatingType::class,
                [
                    'choices' => $this->getChoices($annotation),
                ]
            )
            ->add('entityType', HiddenType::class, ['attr' => ['class' => 'entity-type'], 'data' => $entityType])
            ->add('entityId', HiddenType::class, ['attr' => ['class' => 'entity-id'], 'data' => $entityId])
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