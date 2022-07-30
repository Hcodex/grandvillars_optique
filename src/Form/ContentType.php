<?php

namespace App\Form;

use App\Entity\Content;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options["category"] == "serviceItem" || $options["category"] == "jobItem" || $options["category"] == 'certificationItem'|| $options["category"] == 'infoMessage') {
            $builder->add('icon', TextType::class, [
                'empty_data' => '',
                'label' => 'Icone',
            ]);
        };
        $builder
            ->add('title', TextType::class, [
                'empty_data' => '',
                'label' => $this->SetLabel($options["category"]),
            ]);
        if ($options["category"] != "jobItem" && $options["category"] != "certificationItem") {
            $builder
                ->add('content', CKEditorType::class, [
                    'empty_data' => '',
                    'label' => 'Contenu',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
            'category' => null,
        ]);
    }

    public function SetLabel($category)
    {
        if ($category == "quoteSectionContent") {
            return "Auteur";
        } elseif ($category == "certificationItem") {
            return "Texte";
        } else {
            return "Titre";
        }
    }
}
