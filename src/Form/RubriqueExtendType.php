<?php

namespace App\Form;

use App\Entity\Rubrique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RubriqueExtendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'label' => 'Catégories',
                'required' => false,
                'multiple' => true,
                'label_attr' => ['class' => 'font-weight-bold'],
                'choices' => [
                    'Catégories liées à des actualités' => [
                        'Appel à participation' => 'Appel à participation', 'Associations' => 'Associations', 'Cadre de vie' => 'Cadre de vie', 'Campagne d\'informations' => 'Campagne d\'informations', 'Commémoration' => 'Commémoration', 'Concertation' => 'Concertation', 'Conseils de quartier' => 'Conseils de quartier', 'Culture' => 'Culture', 'Découvrir notre ville' => 'Découvrir notre ville', 'Démarches et formalités' => 'Démarches et formalités', 'Démocratie participative' => 'Démocratie participative', 'Déplacements' => 'Déplacements', 'Développement durable' => 'Développement durable', 'Economie' => 'Economie','Education' => 'Education', 'Elections' => 'Elections', 'Emploi' => 'Emploi', 'Enfance' => 'Enfance', 'Enfance et éducation' => 'Enfance et éducation', 'Etudiants' => 'Etudiants', 'Gastronomie' => 'Gastronomie', 'Handicap' => 'Handicap', 'International' => 'International', 'Loisirs' => 'Loisirs', 'Marchés publics' => 'Marchés publics', 'Patrimoine' => 'Patrimoine', 'Professionnels' => 'Professionnels', 'Projets urbains' => 'Projets urbains', 'Quartiers' => 'Quartiers', 'Santé' => 'Santé', 'Seniors' => 'Seniors', 'Solidarité' => 'Solidarité', 'Sport' => 'Sport', 'Stationnement' => 'Stationnement', 'Tourisme' => 'Tourisme', 'Urbanisme' => 'Urbanisme', 'Vie municipale' => 'Vie municipale',
                    ], 
                    'Catégories liées à des évènements' => [
                        'Animation' => 'Animation', 'Cinéma' => 'Cinéma', 'Concert' => 'Concert', 'Conférence' => 'Conférence', 'Danse' => 'Danse', 'Exposition' => 'Exposition', 'Festival' => 'Festival', 'Livre' => 'Livre', 'Musique' => 'Musique', 'Salon' => 'Salon', 'Solidarité' => 'Solidarité', 'Spectacle' => 'Spectacle', 'Sport' => 'Sport' , 'Théâtre' => 'Théâtre',
                    ],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rubrique::class,
        ]);
    }
}
