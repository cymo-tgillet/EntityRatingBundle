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
                'choices'     => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
                'expanded'    => true,
                'choice_attr' => function ($val, $key, $index) {
                    return [
                        'class' => 'star-rating-item star-rating-item-'.$key,
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