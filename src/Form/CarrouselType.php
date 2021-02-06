<?php

namespace App\Form;

use App\Entity\Carrousel;
use App\Entity\CarrouselObjet;
use Symfony\Component\Form\AbstractType;
use App\Repository\CarrouselObjetRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarrouselType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emplacement1', EntityType::class, [
                'class' => CarrouselObjet::class,
                'query_builder' => function (CarrouselObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                /*'choice_label' => function (CarrouselObjetRepository $repo) {
                    return $repo->findBy([],['id' => 'DESC']);
                },*/
                'label_attr' => ['class' => 'font-weight-bold'],
                //'choice_label' => 'title',
                'choice_label' => function($item) {
                    if (null === $item->getTitle() && null === $item->getLienPublication()) {
                        return 'Sans titre, img: '.preg_replace('/^(.*?)_/','',$item->getCoverImage());
                    } else if (null === $item->getTitle() && null !== $item->getLienPublication()) {
                        return $item->getLienPublication()->getTitle();
                    } else {
                        return $item->getTitle();
                    }
                },
                'required' => false,
            ])
            ->add('emplacement2', EntityType::class, [
                'class' => CarrouselObjet::class,
                'query_builder' => function (CarrouselObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                //'choice_label' => 'title',
                'choice_label' => function($item) {
                    if (null === $item->getTitle() && null === $item->getLienPublication()) {
                        return 'Sans titre, img: '.preg_replace('/^(.*?)_/','',$item->getCoverImage());
                    } else if (null === $item->getTitle() && null !== $item->getLienPublication()) {
                        return $item->getLienPublication()->getTitle();
                    } else {
                        return $item->getTitle();
                    }
                },
                'required' => false,
            ])
            ->add('emplacement3', EntityType::class, [
                'class' => CarrouselObjet::class,
                'query_builder' => function (CarrouselObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                //'choice_label' => 'title',
                'choice_label' => function($item) {
                    if (null === $item->getTitle() && null === $item->getLienPublication()) {
                        return 'Sans titre, img: '.preg_replace('/^(.*?)_/','',$item->getCoverImage());
                    } else if (null === $item->getTitle() && null !== $item->getLienPublication()) {
                        return $item->getLienPublication()->getTitle();
                    } else {
                        return $item->getTitle();
                    }
                },
                'required' => false,
            ])
            ->add('emplacement4', EntityType::class, [
                'class' => CarrouselObjet::class,
                'query_builder' => function (CarrouselObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                //'choice_label' => 'title',
                'choice_label' => function($item) {
                    if (null === $item->getTitle() && null === $item->getLienPublication()) {
                        return 'Sans titre, img: '.preg_replace('/^(.*?)_/','',$item->getCoverImage());
                    } else if (null === $item->getTitle() && null !== $item->getLienPublication()) {
                        return $item->getLienPublication()->getTitle();
                    } else {
                        return $item->getTitle();
                    }
                },
                'required' => false,
            ])
            ->add('emplacement5', EntityType::class, [
                'class' => CarrouselObjet::class,
                'query_builder' => function (CarrouselObjetRepository $repo) {
                    return $repo->findAllByDesc();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                //'choice_label' => 'title',
                'choice_label' => function($item) {
                    if (null === $item->getTitle() && null === $item->getLienPublication()) {
                        return 'Sans titre, img: '.preg_replace('/^(.*?)_/','',$item->getCoverImage());
                    } else if (null === $item->getTitle() && null !== $item->getLienPublication()) {
                        return $item->getLienPublication()->getTitle();
                    } else {
                        return $item->getTitle();
                    }
                },
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Carrousel::class,
        ]);
    }
}
