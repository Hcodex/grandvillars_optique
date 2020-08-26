<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\MediaCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('alt')
        ->add('mediaCategory', EntityType::class, [
            'class' => MediaCategory::class,
            'label' => 'Catégorie',
            'choice_label' => 'name',
            'multiple' => 'true',
            'expanded' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
