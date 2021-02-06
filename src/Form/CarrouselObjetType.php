<?php

namespace App\Form;

use App\Entity\Publication;
use App\Entity\CarrouselObjet;
use Symfony\Component\Form\AbstractType;
use App\Repository\PublicationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CarrouselObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => false, 'attr' => ['placeholder' => 'Veuillez choisir un titre', 'spellcheck' => 'false', 'maxlength' => '100'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('introduction', TextareaType::class, ['label' => 'Introduction', 'required' => false, 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Veuillez décrire la page', 'spellcheck' => 'false', 'rows' => '3', 'style' => 'resize:none; overflow:hidden;']])
            ->add('imageFile', VichImageType::class, ['label' => 'Image de présentation', 'allow_extra_fields' => true, 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => true, 'download_label' => false,'download_uri' => false, 'imagine_pattern' => 'carrousel_cover_edit', 'attr' => ['accept' => '.jpg, .png']])
            ->add('lien_publication', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findAllExceptRubriques();
                },
                'label' => 'Lien vers une publication',
                'group_by' => 'category.Nom',
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'choice_attr' => function($choice, $key, $value) {
                    if(!$choice->getStatut() || $choice->getTrash()) {
                        return ['data-data' => '{"statut":false}'];
                    }
                    else {
                        return ['data-data' => '{"statut":true}'];
                    }    
                },
                'required' => false,
                'placeholder' => 'Veuillez choisir une publication où le lien vous redirigera. (facultatif)',
                'empty_data' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarrouselObjet::class,
        ]);
    }
}
