<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RubriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'attr' => ['placeholder' => 'Veuillez choisir un titre', 'maxlength' => '100'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('introduction', TextareaType::class, ['label' => 'Introduction', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Veuillez décrire la rubrique', 'maxlength' => '500', 'spellcheck' => 'false', 'rows' => '3', 'style' => 'resize:none; overflow:hidden;']])
            ->add('imageFile', VichImageType::class, ['label' => 'Image de couverture', 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false,'imagine_pattern' => 'publication_cover_edit', 'attr' => ['accept' => '.jpg, .png']])
            ->add('liens_utiles_externes', CollectionType::class, [
                'entry_type' => LienUtileExterneType::class,
                'entry_options' => ['label' => false],
                /*'label' => false,*/
                'label_attr' => ['class' => 'font-weight-bold'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true, // permet de ne pas persister les champs vide si false
            ])
            ->add('liens_utiles_internes', CollectionType::class, [
                'error_bubbling' => false,
                'entry_type' => LienUtileInterneType::class,
                'entry_options' => ['label' => false],
                'label_attr' => ['class' => 'font-weight-bold'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true, // permet de ne pas persister les champs vide si false
            ])
            ->add('infos_pratiques', CKEditorType::class, [
                'config_name' => 'ip_config',
                'label' => 'Infos pratiques', 
                'label_attr' => ['class' => 'font-weight-bold'],
                'config' => ['height' => '200', 'placeholder' => 'Laisser cette zone de texte vide si vous n\'avez pas besoin d\'infos pratiques.']
            ])
            ->add('ressources', CollectionType::class, [
                'entry_type' => RessourceType::class,
                'entry_options' => ['label' => false],
                'label_attr' => ['class' => 'font-weight-bold'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false, // permet de ne pas persister les champs vide
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
        ]);
    }
}
