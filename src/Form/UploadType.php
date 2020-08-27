<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\MediaCategory;
use App\Repository\MediaCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alt')
            ->add('imageFile', VichFileType::class)
            ->add('mediaCategory', EntityType::class, [
                'class' => MediaCategory::class,
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'multiple' => 'true',
                'expanded' => true,
                'query_builder' => function (MediaCategoryRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->andWhere("u.name = 'Marque' OR u.name = 'Mutuelle' OR u.name = 'Autre' ");
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
