<?php

namespace App\Form;

use App\Entity\AccesRapide;
use App\Entity\AccesRapideObjet;
use Symfony\Component\Form\AbstractType;
use App\Repository\AccesRapideObjetRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccesRapideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('emplacement1', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement2', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement3', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement4', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement5', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement6', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement7', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement8', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement9', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ->add('emplacement10', EntityType::class, [
            'class' => AccesRapideObjet::class,
            'query_builder' => function (AccesRapideObjetRepository $repo) {
                return $repo->findAllByDesc();
            },
            'label_attr' => ['class' => 'font-weight-bold'],
            'choice_label' => 'title',
            'required' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccesRapide::class,
        ]);
    }
}
