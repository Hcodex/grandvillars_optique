<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{


    public const HONEYPOT_FIELD_NAME = 'email';
    public const EMAIL_FIELD_NAME = 'name';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::EMAIL_FIELD_NAME, EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre adresse mail',
                ],
            ])
            ->add(self::HONEYPOT_FIELD_NAME, TextType::class, [
                'required' => false
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Objet',
                'choices' => [
                    "Demande d'information" => 'Demande d\'information',
                    "Prise de rendez vous" => 'Prise de rendez vous',
                    "Commande" => 'Commande',
                    "Autre demande" => 'Autre demande',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre message',
                ],
            ])
            ->add('policy', CheckboxType::class, [
                'label'    => 'En cochant cette case vous attestez accepter notre politique de confidentialité',
                'required' => true,
            ])
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
