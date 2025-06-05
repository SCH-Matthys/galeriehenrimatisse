<?php

namespace App\Form;

use App\Entity\Artwork;
use App\Entity\ArtworkImage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArtworkImageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('imageFile', FileType::class, [
            //     'label' => 'Image',
            //     'required' => true,
            // ]);
            ->add("imageFile", FileType:: class, [
                "required" => true,
                "mapped" => true,
                "multiple" => false,
                "constraints" => [
                    new File([
                        "maxSize" => "2M",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png",
                            "image/jpg",
                            "image/gif",
                            "image/webp",
                        ],
                        "mimeTypesMessage" => "Veuillez utiliser un format d'image valide (JPEG, JPG, PNG, GIF, WEBP)",
                    ])
                ],
            ]);
        }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArtworkImage::class,
        ]);
    }
}
