<?php

namespace App\Form;

use App\Entity\TimeTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monAm')
            ->add('monPm')
            ->add('tueAm')
            ->add('tuePm')
            ->add('wedAm')
            ->add('wedPm')
            ->add('thuAm')
            ->add('thuPm')
            ->add('friAm')
            ->add('friPm')
            ->add('satAm')
            ->add('satPm')
            ->add('sunAm')
            ->add('sunPm')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeTable::class,
        ]);
    }
}
