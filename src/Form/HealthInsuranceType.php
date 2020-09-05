<?php

namespace App\Form;

use App\Entity\HealthInsurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HealthInsuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de la mutuelle',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Masqué' => 0,
                    'Couvert' => 1,
                    'Non couvert' => 2,
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HealthInsurance::class,
        ]);
    }
}
