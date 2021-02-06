<?php

namespace App\Form;

use App\Entity\FlashInfo;
use App\Entity\FlashInfoObjet;
use Symfony\Component\Form\AbstractType;
use App\Repository\FlashInfoObjetRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlashInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emplacement1', EntityType::class, [
                'class' => FlashInfoObjet::class,
                'query_builder' => function (FlashInfoObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('emplacement2', EntityType::class, [
                'class' => FlashInfoObjet::class,
                'query_builder' => function (FlashInfoObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('emplacement3', EntityType::class, [
                'class' => FlashInfoObjet::class,
                'query_builder' => function (FlashInfoObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('emplacement4', EntityType::class, [
                'class' => FlashInfoObjet::class,
                'query_builder' => function (FlashInfoObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('emplacement5', EntityType::class, [
                'class' => FlashInfoObjet::class,
                'query_builder' => function (FlashInfoObjetRepository $repo) {
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
            'data_class' => FlashInfo::class,
        ]);
    }
}
