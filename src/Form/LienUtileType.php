<?php

namespace App\Form;

use App\Entity\LienUtile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LienUtileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('url', UrlType::class, ['label' => false, 'required' => true, 'attr' => ['placeholder' => 'Veuillez saisir une url']])
        ->add('title', TextType::class, ['label' => false, 'required' => true, 'attr' => ['placeholder' => 'Veuillez saisir un titre pour ce lien', 'class' => 'mb-1', 'maxlength' => '80']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LienUtile::class,
        ]);
    }
}
