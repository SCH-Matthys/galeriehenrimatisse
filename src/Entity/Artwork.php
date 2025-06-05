<?php

namespace App\Entity;

use App\Repository\ArtworkRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File; 
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ArtworkRepository::class)]
class Artwork
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'artworks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gallery $gallery = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): static
    {
        $this->gallery = $gallery;

        return $this;
    }

    // Ajouts pour les images avec le bundle Vich
    // Ajouts pour les images avec le bundle Vich

    // #[Vich\UploadableField(mapping: "artwork_image", fileNameProperty: "imageName")]
    // private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, ArtworkImage>
     */
    #[ORM\OneToMany(targetEntity: ArtworkImage::class, mappedBy: 'artwork', cascade: ["persist", "remove"])]
    private Collection $artworkImages;

    public function __construct()
    {
        $this->artworkImages = new ArrayCollection();
    }

    public function addImage(ArtworkImage $image): void
    {
        $this->artworkImages[] = $image;
        $image->setArtwork($this);
    }

    public function getImages(): Collection
    {
        return $this->artworkImages;
    }
    // public function setImageFile(?File $imageFile = null): void
    // {
    //     $this->imageFile = $imageFile;

    //     if($imageFile){
    //         $this->updatedAt = new \DateTimeImmutable();
    //     }
    // }

    // public function getImageFile(): ?File
    // {
    //     return $this->imageFile;
    // }

    // public function setImageName(?string $imageName): void
    // {
    //     $this->imageName = $imageName;
    // }

    // public function getImageName(): ?string
    // {
    //     return $this->imageName;
    // }

    /**
     * @return Collection<int, ArtworkImage>
     */
    public function getArtworkImages(): Collection
    {
        return $this->artworkImages;
    }

    public function addArtworkImage(ArtworkImage $artworkImage): static
    {
        if (!$this->artworkImages->contains($artworkImage)) {
            $this->artworkImages->add($artworkImage);
            $artworkImage->setArtwork($this);
        }

        return $this;
    }

    public function removeArtworkImage(ArtworkImage $artworkImage): static
    {
        if ($this->artworkImages->removeElement($artworkImage)) {
            // set the owning side to null (unless already changed)
            if ($artworkImage->getArtwork() === $this) {
                $artworkImage->setArtwork(null);
            }
        }

        return $this;
    }
}
