<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("firstName", TextType::class, [
                "label" => "Saisisez votre prénom",
                "attr"=>["placeholder" => "Votre prénom"]  
            ])
            ->add("lastName", TextType::class, [
                "label" => "Saisiez votre nom",
                "attr"=>["placeholder" => "Votre nom"]  
            ])
            ->add("email", EmailType::class, [
                "label" => "Entrez votre adresse mail",
                "attr"=>["placeholder" => "Votre adresse mail"]  
            ])
            ->add("phone", NumberType::class, [
                "label" => "Entrez votre numero de téléphone",
                "attr"=>["placeholder" => "Votre numero de téléphone"]  
            ])
            ->add("message", TextareaType::class, [
                "label" => "Entrez votre message",
                "attr"=>["placeholder" => "Entrez votre message"]  
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer"
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
