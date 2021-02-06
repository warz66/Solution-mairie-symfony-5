<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subtitle', TextType::class, ['label' => 'Sous-titre', 'attr' => ['placeholder' => 'Informations sur la date et le lieu de l\'événement', 'maxlength' => '255'], 'label_attr' => ['class' => 'font-weight-bold']])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'label_attr' => ['class' => 'font-weight-bold'],
                'choices' => ['Animation' => 'Animation', 'Cinéma' => 'Cinéma', 'Concert' => 'Concert', 'Conférence' => 'Conférence', 'Danse' => 'Danse', 'Exposition' => 'Exposition', 'Festival' => 'Festival', 'Livre' => 'Livre', 'Musique' => 'Musique', 'Salon' => 'Salon', 'Solidarité' => 'Solidarité', 'Spectacle' => 'Spectacle', 'Sport' => 'Sport' , 'Théâtre' => 'Théâtre']
            ])
            ->add('debut_evenement', DateType::class, [
                'label' => 'Début de l\'événement',
                'label_attr' => ['class' => 'font-weight-bold'],
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker', 'autocomplete' => 'off', 'placeholder' => 'Veuillez choisir une date'],
                'html5' => false,
                'format' => 'dd-MM-yyyy'
            ])
            ->add('fin_evenement', DateType::class, [
                'label' => 'Fin de l\'événement',
                'label_attr' => ['class' => 'font-weight-bold'],
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker', 'autocomplete' => 'off', 'placeholder' => 'Durée de fin d\'événement indéfinie'],
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'required' => false,
            ])
            ->add('statut', CheckboxType::class, [
                'required' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
