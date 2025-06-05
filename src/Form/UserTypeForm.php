<?php

namespace App\Form;

use App\Entity\User;
use Composer\Semver\Constraint\Constraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(['message' => 'Veuillez entrer un email valide.']),
                ],
                "attr" => [
                    "placeholder" => "Entrez votre adresse mail"
                ]
            ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent être identiques.',
            'required' => true,
            'first_options'  => [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Entrez votre mot de passe'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le mot de passe est obligatoire.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule et un symbole.',
                    ]),
                ],
            ],
            'second_options' => [
                'label' => 'Confirmez le mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe'
                ],
            ],
            'mapped' => false,
        ])
            ->add('firstName', TextType::class, [
                "attr" => [
                    "placeholder" => "Entrez votre prénom"
                ],
            ])
            ->add('lastName', TextType::class, [
                "attr" => [
                    "placeholder" => "Entrez votre nom"
                ],
            ])
            ->add('alias', TextType::class, [
                "attr" => [
                    "placeholder" => "Entrez votre alias/nom d'artiste"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
