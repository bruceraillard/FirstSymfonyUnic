<?php

namespace App\Form;

use App\Entity\Auteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and edit Auteurs entities.
 */
class AuthorType extends AbstractType
{
    /**
     * Build the form fields for the author.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Field for the author's surname
            ->add('surname')
            // Field for the author's first name
            ->add('firstname')
            // Date field for the author's birthdate, rendered as a single text input
            ->add('birthdate', null, [
                'widget' => 'single_text',
            ]);
    }

    /**
     * Configure the form options, setting the data_class to Auteurs.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteurs::class,
        ]);
    }
}
