<?php

namespace App\Form;

use App\Entity\CardsList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('boxesNumber', ChoiceType::class, [
                'choices' => [
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CardsList::class,
        ]);
    }
}
