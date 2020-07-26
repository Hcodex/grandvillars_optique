<?php

namespace App\Form;

use App\Entity\TimeTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monAm', TextType::class, [
                'label' => 'Lundi matin',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('monPm', TextType::class, [
                'label' => 'Lundi après midi',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('tueAm', TextType::class, [
                'label' => 'Mardi matin',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('tuePm', TextType::class, [
                'label' => 'Mardi après midi',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('wedAm', TextType::class, [
                'label' => 'Mercredi matin',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('wedPm', TextType::class, [
                'label' => 'Mercredi après midi',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('thuAm', TextType::class, [
                'label' => 'Jeudi matin',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('thuPm', TextType::class, [
                'label' => 'Jeudi après midi',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('friAm', TextType::class, [
                'label' => 'Vendredi matin',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('friPm', TextType::class, [
                'label' => 'Vendredi après midi',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('satAm', TextType::class, [
                'label' => 'Samedi matin',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('satPm', TextType::class, [
                'label' => 'Samedi après midi',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('sunAm', TextType::class, [
                'label' => 'Dimanche matin',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
            ->add('sunPm', TextType::class, [
                'label' => 'Dimanche après midi',
                'row_attr' => [
                    'class' => 'col-6'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeTable::class,
        ]);
    }
}
