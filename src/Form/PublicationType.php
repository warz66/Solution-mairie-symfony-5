<?php

namespace App\Form;

use App\Entity\Galerie;
use App\Entity\Ressource;
use App\Entity\Publication;
use App\Service\MessageDependenciesService;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PublicationType extends AbstractType
{   
    private $md;
    
    public function __construct(MessageDependenciesService $md) {
        $this->md = $md;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'attr' => ['placeholder' => 'Veuillez choisir un titre', 'spellcheck' => 'false', 'maxlength' => '100'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('introduction', TextareaType::class, ['label' => 'Introduction', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Veuillez décrire la page', 'spellcheck' => 'false', 'maxlength' => '500', 'rows' => '3', 'style' => 'resize:none; overflow:hidden;']])
            ->add('statut', CheckboxType::class, ['required' => false, 'attr' => ['msgdependencies' => $this->md->getMsgPublicationDependencies($builder->getData())]])
            ->add('imageFile', VichImageType::class, ['label' => 'Image de couverture', 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false, 'imagine_pattern' => 'publication_cover_edit', 'attr' => ['accept' => '.jpg, .png']])
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
                /*'label' => false,*/
                'label_attr' => ['class' => 'font-weight-bold'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true, // permet de ne pas persister les champs vide si false
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
            ->add('infos_pratiques', CKEditorType::class, [
                'config_name' => 'ip_config',
                'label' => 'Infos pratiques', 
                'label_attr' => ['class' => 'font-weight-bold'],
                'config' => ['height' => '200', 'placeholder' => 'Laisser cette zone de texte vide si vous n\'avez pas besoin d\'infos pratiques.']
            ])
            ->add('galeries', EntityType::class, [
                'class' => Galerie::class,
                'choice_attr' => function($choice, $key, $value) {
                    if(!$choice->getStatut() || $choice->getTrash()) {
                        return ['data-data' => '{"statut":false}'];
                    }
                    else {
                        return ['data-data' => '{"statut":true}'];
                    }    
                },
                'label_attr' => ['class' => 'font-weight-bold'],
                'choice_label' => 'title',
                'multiple' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
