<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @Vich\Uploadable
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $alt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Assert\File(
     *     maxSize = "3M",
     *     maxSizeMessage = "Le fichier sélectionner est trop volumineux Max 3Mo",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif", "image/jpg", "video/mp4", "image/svg+xml"},
     *     mimeTypesMessage = "Le format du fichier sélectionné n'est pas supporté",
     * )
     * 
     * @Vich\UploadableField(mapping="media", fileNameProperty="imageName", size="imageSize")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=MediaCategory::class, inversedBy="mediaId")
     * @ORM\JoinTable(name="media_media_category",
     *      joinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")},
     *       inverseJoinColumns={@ORM\JoinColumn(name="media_category_id", referencedColumnName="id")})                         
     */
    private $mediaCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->mediaCategory = new ArrayCollection();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return Collection|MediaCategory[]
     */
    public function getMediaCategory(): Collection
    {
        return $this->mediaCategory;
    }



    public function getCategories()
    {
        $categories= $this->mediaCategory->map(function ($category) {
            return $category->getName();
        })->toArray();

        return $categories;
    }





    public function addMediaCategory(MediaCategory $mediaCategory): self
    {
        if (!$this->mediaCategory->contains($mediaCategory)) {
            $this->mediaCategory[] = $mediaCategory;
        }

        return $this;
    }

    public function removeMediaCategory(MediaCategory $mediaCategory): self
    {
        if ($this->mediaCategory->contains($mediaCategory)) {
            $this->mediaCategory->removeElement($mediaCategory);
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
