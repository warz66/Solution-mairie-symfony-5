<?php

namespace App\Form;

use App\Entity\KiosqueObjet;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class KiosqueObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => true, 'attr' => ['placeholder' => 'Veuillez saisir le titre de la revue', 'spellcheck' => 'false', 'maxlength' => '60'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('urlFile', VichFileType::class, ['label' => 'Fichier Pdf', 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false, 'attr' => ['accept' => '.pdf']])
            ->add('statut', CheckboxType::class, ['required' => false])
            ->add('parution', DateType::class, [
                'label' => 'Parution',
                'label_attr' => ['class' => 'font-weight-bold'],
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker', 'autocomplete' => 'off', 'placeholder' => 'Veuillez indiquer la date de parution de la revue'],
                'html5' => false,
                'format' => 'dd-MM-yyyy'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KiosqueObjet::class,
        ]);
    }
}
