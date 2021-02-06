<?php

namespace App\Form;

use App\Entity\FormulaireContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormulaireContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class, ['label' => 'Email', 'attr' => ['placeholder' => 'Votre adresse e-mail ...', 'maxlength' => '320'],'label_attr' => ['class' => 'font-weight-bold']])
        ->add('objet', TextType::class, ['label' => 'Objet', 'attr' => ['placeholder' => 'L\'objet de votre message ...', 'maxlength' => '255'],'label_attr' => ['class' => 'font-weight-bold']])
        ->add('message', TextareaType::class, ['label' => 'Message', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Le contenu de votre message ...', 'maxlength' => '2500', 'rows' => '7']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormulaireContact::class,
            'allow_extra_fields' => true,
        ]);
    }
}
