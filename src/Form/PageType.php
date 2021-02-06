<?php

namespace App\Form;

use App\Entity\Ressource;
use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use App\Repository\PublicationRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'attr' => ['placeholder' => 'Veuillez choisir un titre', 'maxlength' => '100'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('introduction', TextareaType::class, ['label' => 'Introduction', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Veuillez décrire la page', 'spellcheck' => 'false', 'rows' => '3', 'style' => 'resize:none; overflow:hidden;']])
            ->add('statut', CheckboxType::class, ['required' => false])
            ->add('imageFile', VichImageType::class, ['label' => 'Image de couverture', 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false,'imagine_pattern' => 'publication_cover_edit', 'attr' => ['accept' => '.jpg, .png']])
            ->add('liens_utiles', CollectionType::class, [
                'entry_type' => LienUtileType::class,
                'entry_options' => ['label' => false],
                /*'label' => false,*/
                'label_attr' => ['class' => 'font-weight-bold'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true, // permet de ne pas persister les champs vide si false
            ])
            ->add('parent', EntityType::class, [
                'class' => Publication::class,
                'query_builder' => function (PublicationRepository $repo) {
                    return $repo->findAllRubrique();
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('ressources', CollectionType::class, [
                'entry_type' => RessourceType::class,
                'entry_options' => ['label' => false],
                /*'label' => false,*/
                'label_attr' => ['class' => 'font-weight-bold'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true, // permet de ne pas persister les champs vide si false
                'delete_empty' => function (Ressource $ressource = null) { // permet de ne pas persister si l'input url est vide
                    return null === $ressource || empty($ressource->getUpdatedAt());
                }
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu principal', 
                'label_attr' => ['class' => 'font-weight-bold'],
                'config' => ['height' => '500']
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'publication';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
            //'pagination' => null,
        ]);
    }
}
