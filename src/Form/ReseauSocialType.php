<?php

namespace App\Form;

use App\Entity\ReseauSocial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReseauSocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom', 'required' => true, 'attr' => ['class' => 'mb-1', 'placeholder' => 'Veuillez saisir le nom du réseau social', 'spellcheck' => 'false', 'maxlength' => '30'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('url', UrlType::class, ['label' => 'Url', 'required' => true, 'attr' => ['class' => 'mb-1', 'placeholder' => 'Veuillez saisir l\'adresse url vers vorte réseau social', 'spellcheck' => 'false', 'maxlength' => '255']])
            ->add('icone', TextType::class, ['label' => 'Icône', 'required' => false, 'attr' => ['class' => 'd-none ReseauSocialIcon']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReseauSocial::class,
        ]);
    }
}
