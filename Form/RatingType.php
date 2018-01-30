<?php

namespace Cymo\Bundle\EntityRatingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{

    const STAR_NUMBER = 5;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'label'       => false,
                'attr'        => ['class' => 'rating'],
                'choices'     => [5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1],
                'expanded'    => true,
                'choice_attr' => function ($val, $key, $index) {
                    return [
                        'class' => 'star-rating-item',
                    ];
                },
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}