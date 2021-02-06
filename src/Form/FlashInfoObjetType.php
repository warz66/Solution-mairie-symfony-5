<?php

namespace App\Form;

use App\Entity\Publication;
use App\Entity\FlashInfoObjet;
use Symfony\Component\Form\AbstractType;
use App\Repository\PublicationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FlashInfoObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'attr' => ['placeholder' => 'Veuillez choisir un titre court et percutant.', 'spellcheck' => 'false', 'maxlength' => '60'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('information', TextareaType::class, ['label' => 'Information', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Veuillez décrire une information claire et concise.', 'spellcheck' => 'false', 'maxlength' => '180', 'rows' => '3', 'style' => 'resize:none; overflow:hidden;']])
            ->add('choix_lien', ChoiceType::class, [
                'label' => 'Nature du lien',
                'label_attr' => ['class' => 'font-weight-bold'],
                'choices' => ['Sans lien' => null,'Lien externe' => true,'Lien interne' => false],
                'placeholder' => false,
                'required' => false,
            ])
            ->add('lien_interne', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findAllExceptRubriques();
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
                'choice_label' => 'title',
                'required' => true,
                'placeholder' => 'Veuillez choisir une publication où le flash info vous redirigera.',
            ])
            ->add('lien_externe', UrlType::class, [
                'label' => false,
                'required' => true, 
                'attr' => ['placeholder' => 'Veuillez saisir une url'],
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FlashInfoObjet::class,
        ]);
    }
}
