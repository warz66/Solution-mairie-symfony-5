<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use App\Repository\PublicationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('rubrique1', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('rubrique2', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('rubrique3', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('rubrique4', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('rubrique5', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('rubrique6', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('rubrique7', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('rubrique8', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findRubrique();
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
            'data_class' => Menu::class,
        ]);
    }
}
