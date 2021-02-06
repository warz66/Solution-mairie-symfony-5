<?php

namespace App\Form;

//use App\Entity\ResetPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs "Mot de passe" et "Confirmation du mot de passe" doivent être identiques',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe', 'attr' => ['placeholder' => 'Au moins 8 caractères minimum']],
                'second_options' => ['label' => 'Confirmation du mot de passe', 'attr' => ['placeholder' => "Veuillez répéter le mot de passe à l'identique"]]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => ResetPassword::class,
        ]);
    }
}
