<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\MediaCategory;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityCategories = $builder->getData()->getCategories();

        $builder
            ->add('imageFile', VichFileType::class, [
                'label' => 'Image',
                'allow_delete' => false,
                'required' => false,
            ])
            ->add('alt', TextType::class, [
                'label' => 'Texte alternatif de l\'image',
            ])
            ->add('mediaCategory', EntityType::class, [
                'class' => MediaCategory::class,
                'label' => "",
                'choice_label' => 'name',
                'multiple' => 'true',
                'expanded' => true,
                'row_attr' => [
                    'class' => 'd-none',
                ],
            ]);


        if (in_array("socialNetwork", $entityCategories)) {
            $builder
                ->add('title', TextType::class, [
                    'label' => 'Nom du réseau social',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champ ne peut être vide',
                        ]),
                    ],
                ])
                ->add('description', TextType::class, [
                    'label' => 'Url du profil',
                    'constraints' => [
                        new Url([
                            'message' => 'Le format de l\'url est invalide',
                        ]),
                    ],
                ]);
        }


        if (in_array("mutuelle", $entityCategories)) {
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
        ]);
    }
}
