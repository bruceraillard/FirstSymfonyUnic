<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surname')
            ->add('firstname')
            ->add('adresses', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => function (Adresse $adresse) {
                    return $adresse->getAdresse() . ' (' . $adresse->getPostalcode() . ', ' . $adresse->getCountry() . ')';
                },
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
