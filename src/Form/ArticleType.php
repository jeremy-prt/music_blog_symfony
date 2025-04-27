<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('slug')
            ->add('artiste')
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Chronique' => 'Chronique',
                    'Actu' => 'Actu',
                    'Playlist' => 'Playlist',
                    'Interview' => 'Interview',
                    'Découverte' => 'Découverte',
                    'Live' => 'Live',
                    'Classement' => 'Classement',
                    'Sortie' => 'Sortie',
                    'Analyse' => 'Analyse',
                    'Insolite' => 'Insolite'
                ],
                'placeholder' => 'Choisir une catégorie',
                'required' => false,
                'label' => 'Catégorie'
            ])
            ->add('contenu');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}