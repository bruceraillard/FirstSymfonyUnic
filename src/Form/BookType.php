<?php

namespace App\Form;

use App\Entity\Auteurs;
use App\Entity\Livres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form fields for creating and editing Livres entities.
 */
class BookType extends AbstractType
{
    /**
     * Build the book form with title, publication date, publishing house, and author selection.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Input for the book title with custom label
            ->add('title', null, [
                'label' => 'book.manage.field.title',
            ])
            // Date input for publication date, rendered as a single text widget
            ->add('publicationdate', null, [
                'widget' => 'single_text',
                'label' => 'book.manage.field.publication_date',
            ])
            // Input for the publishing house with custom label
            ->add('publishinghouse', null, [
                'label' => 'book.manage.field.publishing_house',
            ])
            // Dropdown to select an author, displaying surname and firstname
            ->add('auteur', EntityType::class, [
                'class' => Auteurs::class,
                'choice_label' => function (Auteurs $auteur) {
                    return $auteur->getSurname() . ' ' . $auteur->getFirstname();
                },
                'label' => 'book.manage.field.author',
            ]);
    }

    /**
     * Configure default options for the form, binding it to the Livres entity.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
            'translation_domain' => 'messages',
        ]);
    }
}