<?php

namespace App\Form;

use App\Entity\EventArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventArticleTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add("title", TextType::class, [
            "label" => "Titre de l'événement",
            "attr" => [
                "placeholder" => "Titre de l'événement",
            ],
        ])
        ->add("summary", TextType::class, [
            "label" => "Résumé de l'événement",
            "attr" => [
                "placeholder" => "Résumé court de l'événement",
            ],
        ])
        ->add('imageFile', FileType::class, [
            'required' => false,
            'mapped' => true,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/jpg',
                        'image/png',
                        'image/gif',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Formats valides : JPEG JPG PNG GIF WEBP',
                ])
            ],
            // 'download_label' => true,
        ])
        ->add("content", TextareaType::class, [
            "label" => "Contenu détailé de l'événement",
            "attr" => [
                "placeholder" => "Détaillez l'événement ici...",
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventArticle::class,
        ]);
    }
}
