<?php

namespace App\Form;

use App\Entity\Information;
use App\Form\ReseauSocialType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', TextType::class, ['attr' => [ 'maxlength' => '255'], 'label_attr' => ['class' => 'font-weight-bold'], 'required' => true])
            ->add('cp', TextType::class, ['label' => 'Code postal', 'attr' => [ 'maxlength' => '10'], 'label_attr' => ['class' => 'font-weight-bold'], 'required' => true])
            ->add('ville', TextType::class, ['attr' => [ 'maxlength' => '40'], 'label_attr' => ['class' => 'font-weight-bold'], 'required' => true])
            ->add('telephone', TextType::class, ['label' => 'Téléphone', 'attr' => [ 'maxlength' => '20'], 'label_attr' => ['class' => 'font-weight-bold'], 'required' => true])
            ->add('horaire', CKEditorType::class, [
                'config_name' => 'ip_config',
                'label_attr' => ['class' => 'font-weight-bold'],
                'config' => ['height' => '200', 'placeholder' => 'Si besoin, saisissez les horaires pour votre mairie.']
            ])
            ->add('complement', CKEditorType::class, [
                'config_name' => 'ip_config',
                'label' => 'Présentation',
                'label_attr' => ['class' => 'font-weight-bold'],
                'config' => ['height' => '200', 'placeholder' => 'Si besoin, saisissez un texte de présentation pour votre mairie.']
            ])
            ->add('reseaux_sociaux', CollectionType::class, [
                'entry_type' => ReseauSocialType::class,
                'entry_options' => ['label' => false],
                /*'label' => false,*/
                'label_attr' => ['class' => 'font-weight-bold'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Information::class,
        ]);
    }
}
