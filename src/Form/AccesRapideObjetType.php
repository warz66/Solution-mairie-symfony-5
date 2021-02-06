<?php

namespace App\Form;

use App\Entity\Publication;
use App\Entity\AccesRapideObjet;
use Symfony\Component\Form\AbstractType;
use App\Repository\PublicationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccesRapideObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => true, 'attr' => ['placeholder' => 'Veuillez saisir un titre', 'spellcheck' => 'false', 'maxlength' => '30'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('icone', TextType::class, ['label' => 'Icône', 'required' => false,'attr' => ['class' => 'd-none'], 'label_attr' => ['class' => 'font-weight-bold']])
            ->add('lien_publication', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findSousRubriqueAndPage();
                },
                'choice_attr' => function($choice, $key, $value) {
                    if(!$choice->getStatut() || $choice->getTrash()) {
                        return ['data-data' => '{"statut":false}'];
                    }
                    else {
                        return ['data-data' => '{"statut":true}'];
                    }    
                },
                'label' => 'Lien vers une publication',
                'group_by' => 'category.Nom',
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => true,
                'placeholder' => 'Veuillez choisir une publication où le lien vous redirigera.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccesRapideObjet::class,
        ]);
    }
}
