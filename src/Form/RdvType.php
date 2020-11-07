<?php

namespace App\Form;

use App\Entity\Rdv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RdvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre adresse mail',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Objet',
                'choices' => [
                    'Contrôler ma vue et choisir mes lunettes' => 'Contrôle de la vue + lunettes',
                    'Contrôler ma vue' => 'Contrôle de la vue',
                    'Être conseillé dans le choix de mes lunettes' => 'Choix lunettes',
                    "Être conseillé dans le choix de lunettes pour mon enfant" => 'Choix lunettes enfant',
                    'Être accompagné dans la manipulation et l\'entretien de mes lentilles' => 'Consultation lentilles',
                    'Retirer une commande en magasin' => 'Retrait commande',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message (facultatif)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Vous pouvez laisser un message',
                ],
            ])
            ->add('slot1', TextType::class, [
                'required'   => false,
                'label' => false,
                'attr' => [
                    'placeholder' => '-',
                    'class' => 'd-none',
                ],
            ])
            ->add('slot2', TextType::class, [
                'required'   => false,
                'label' => false,
                'attr' => [
                    'placeholder' => '-',
                    'class' => 'd-none',
                ],
            ])
            ->add('slot3', TextType::class, [
                'required'   => false,
                'label' => false,
                'attr' => [
                    'placeholder' => '-',
                    'class' => 'd-none',
                ],
            ])
            ->add('policy', CheckboxType::class, [
                'label'    => 'En cochant cette case vous attestez accepter notre [link]politique de confidentialité[/link]',
                'required' => true,
            ])
            ->add('age', CheckboxType::class, [
                'label'    => 'Vous confirmez que le rendez-vous concerne une personne de plus de 16 ans',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,

            'validation_groups' => function (FormInterface $form) {

                $data = $form->getData();
                if ($data->getSubject() == "Contrôle de la vue" || $data->getSubject() == "Contrôle de la vue + lunettes" ) {
                    return ['Default', 'examVue'];
                }
                return ['Default'];
            }

        ]);
    }
}
