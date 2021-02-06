<?php

namespace App\Form;

use App\Entity\Ressource;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RessourceType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        /*$builder
        //->add('urlFile', VichFileType::class, ['label' => false, 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false, 'attr' => ['accept' => '.pdf']])
        ;*/
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            if (null != $event->getData()) {
                $ressource = $event->getData();
                $form = $event->getForm();
                $form->add('urlFile', VichFileType::class, ['label' => false, 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false, 'attr' => ['data-rscfile' => $ressource->getUrl() ,'accept' => '.pdf']])
                     ->add('title', TextType::class, ['label' => false, 'required' => true, 'attr' => ['placeholder' => 'Veuillez saisir un titre pour cette ressource', 'class' => 'mb-1', 'maxlength' => '80']]);   
            } else {
                $form = $event->getForm();
                $form->add('urlFile', VichFileType::class, ['label' => false, 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false, 'attr' => ['accept' => '.pdf']])
                     ->add('title', TextType::class, ['label' => false, 'required' => true, 'attr' => ['placeholder' => 'Veuillez saisir un titre pour cette ressource', 'class' => 'mb-1', 'maxlength' => '80']]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}
