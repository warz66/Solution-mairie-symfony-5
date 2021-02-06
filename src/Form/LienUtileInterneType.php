<?php

namespace App\Form;

use App\Entity\Publication;
use App\Entity\LienUtileInterne;
use Symfony\Component\Form\AbstractType;
use App\Repository\PublicationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LienUtileInterneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lien_publication', EntityType::class, [
                'class' => Publication::class,
                /*'choice_label' => function (PublicationRepository $repo) {
                    return $repo->findAll();
                },*/
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findAllByCategory();
                },
                'choice_attr' => function($choice, $key, $value) {
                    if(!$choice->getStatut() || $choice->getTrash()) {
                        return ['data-data' => '{"statut":false}'];
                    }
                    else {
                        return ['data-data' => '{"statut":true}'];
                    }    
                },
                'label' => false,
                'group_by' => 'category.Nom',
                'attr' => ['class' => 'publication_liens_utiles_internes'],
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => true,
                'placeholder' => 'Veuillez choisir une publication où le lien vous redirigera.',
                'empty_data' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LienUtileInterne::class,
        ]);
    }
}
