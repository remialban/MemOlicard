<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'form.default.first_name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'form.default.last_name',
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.default.email',
            ])
            ->add('username', TextType::class, [
                'label' => 'form.default.username',
            ])
            ->add('modifiedPassword', PasswordType::class, [
                'label' => 'form.default.password',
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'form.default.confirm_password',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['security_register']
        ]);
    }
}
