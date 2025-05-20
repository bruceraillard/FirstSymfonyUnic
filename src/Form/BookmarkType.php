<?php

namespace App\Form;

use App\Entity\Bookmark;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and edit Bookmark entities.
 */
class BookmarkType extends AbstractType
{
    /**
     * Build the bookmark form fields.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // URL input for the bookmark link
            ->add('url')
            // Text area for user comment, with a custom label
            ->add('comment', null, [
                'label' => 'bookmark.manage.field.comment',
            ])
            // Multi-select field for assigning tags to the bookmark
            ->add('Tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'bookmark.manage.field.tags',
            ]);
    }

    /**
     * Configure options for the bookmark form.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Bind this form to the Bookmark entity
            'data_class' => Bookmark::class,
            // Use the default translation domain for labels
            'translation_domain' => 'messages',
        ]);
    }
}