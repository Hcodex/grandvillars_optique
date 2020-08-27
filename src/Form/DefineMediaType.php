<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\MediaCategory;
use App\Repository\MediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefineMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('mediaId', EntityType::class, [
            'class' => Media::class,
            'label' => 'Image',
            'choice_label' => 'alt',
            'multiple' => true,
            'expanded' => true,
            'by_reference' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MediaCategory::class,
        ]);
    }
}
