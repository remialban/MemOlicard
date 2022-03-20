<?php

namespace App\Form\Settings\Security;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('modifiedPassword', PasswordType::class, [
                'label' => 'form.default.new_password'
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'form.default.confirm_password'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['security_create_password']
        ]);
    }
}
