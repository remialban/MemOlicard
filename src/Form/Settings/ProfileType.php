<?php

namespace App\Form\Settings;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'form.default.first_name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'form.default.last_name',
            ])
            ->add('biography', TextareaType::class, [
                "required" => false,
                "label" => 'form.default.biography',
                "attr" => [
                    "rows" => 10,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['profile']
        ]);
    }
}
