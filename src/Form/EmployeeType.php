<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and edit Employee entities.
 */
class EmployeeType extends AbstractType
{
    /**
     * Build the form fields for the Employee entity.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Field for the employee's surname
            ->add('surname')
            // Field for the employee's first name
            ->add('firstname')
            // Multi-select field for assigning one or more addresses to the employee
            ->add('adresses', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => function (Adresse $adresse) {
                    // Display each address as "street (postal code, country)"
                    return $adresse->getAdresse() . ' (' . $adresse->getPostalcode() . ', ' . $adresse->getCountry() . ')';
                },
                'multiple' => true,
            ]);
    }

    /**
     * Configure the options for this form, binding it to the Employee class.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}