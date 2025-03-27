<?php

namespace App\Form;

use App\Entity\Auteurs;
use App\Entity\Livres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    /**
     * Builds the form for the Book entity.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Add a text field for the book title
            ->add('title')
            // Add a date field for the publication date with a single text widget
            ->add('publicationdate', null, [
                'widget' => 'single_text',
            ])
            // Add a text field for the publishing house
            ->add('publishinghouse')
            // Add a dropdown field for selecting the author, displaying the surname and firstname
            ->add('auteur', EntityType::class, [
                'class' => Auteurs::class,
                'choice_label' => function ($auteur) {
                    return $auteur->getSurname() . ' ' . $auteur->getFirstname();
                },
            ])
        ;
    }

    /**
     * Configures the options for this form.
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Set the data class to Livres
            'data_class' => Livres::class,
        ]);
    }
}