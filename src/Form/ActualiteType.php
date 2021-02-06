<?php

namespace App\Form;

use App\Entity\Actualite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ActualiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'label_attr' => ['class' => 'font-weight-bold'],
                'choices' => ['Appel à participation' => 'Appel à participation', 'Associations' => 'Associations', 'Cadre de vie' => 'Cadre de vie', 'Campagne d\'informations' => 'Campagne d\'informations', 'Commémoration' => 'Commémoration', 'Concertation' => 'Concertation', 'Conseils de quartier' => 'Conseils de quartier', 'Culture' => 'Culture', 'Découvrir notre ville' => 'Découvrir notre ville', 'Démarches et formalités' => 'Démarches et formalités', 'Démocratie participative' => 'Démocratie participative', 'Déplacements' => 'Déplacements', 'Développement durable' => 'Développement durable', 'Economie' => 'Economie','Education' => 'Education', 'Elections' => 'Elections', 'Emploi' => 'Emploi', 'Enfance' => 'Enfance', 'Enfance et éducation' => 'Enfance et éducation', 'Etudiants' => 'Etudiants', 'Gastronomie' => 'Gastronomie', 'Handicap' => 'Handicap', 'International' => 'International', 'Loisirs' => 'Loisirs', 'Marchés publics' => 'Marchés publics', 'Patrimoine' => 'Patrimoine', 'Professionnels' => 'Professionnels', 'Projets urbains' => 'Projets urbains', 'Quartiers' => 'Quartiers', 'Santé' => 'Santé', 'Seniors' => 'Seniors', 'Solidarité' => 'Solidarité', 'Sport' => 'Sport', 'Stationnement' => 'Stationnement', 'Tourisme' => 'Tourisme', 'Urbanisme' => 'Urbanisme', 'Vie municipale' => 'Vie municipale']
            ])
            ->add('debut_publication', DateType::class, [
                'label' => 'Début de publication',
                'label_attr' => ['class' => 'font-weight-bold'],
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker', 'autocomplete' => 'off', 'placeholder' => 'Veuillez choisir une date'],
                'html5' => false,
                'format' => 'dd-MM-yyyy'
            ])
            ->add('fin_publication', DateType::class, [
                'label' => 'Fin de publication',
                'label_attr' => ['class' => 'font-weight-bold'],
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker', 'autocomplete' => 'off', 'placeholder' => 'Pas de date de fin de publication (illimitée)'],
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actualite::class,
        ]);
    }
}
