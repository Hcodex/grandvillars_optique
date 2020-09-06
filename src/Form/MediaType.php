<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\MediaCategory;
use App\Repository\MediaCategoryRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('imageFile', VichFileType::class, [
                'label' => 'Image',
                'allow_delete' => false,
                'required' => false,
            ])
            ->add('alt', TextType::class, [
                'label' => 'Texte alternatif',
            ]);

        if ($options['type'] == "mutuelle") {
            $builder
                ->add('title', TextType::class, [
                    'label' => 'Titre',
                ])
                ->add('description', CKEditorType::class, [
                    'empty_data' => '',
                    'label' => 'Description',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'type' => 'default',
        ]);
    }
}
