<?php

namespace App\Entity;

use App\Repository\MediaCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaCategoryRepository::class)
 */
class MediaCategory
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Media::class, mappedBy="mediaCategory")
     * @ORM\JoinTable(name="media_media_category",
     *      joinColumns={@ORM\JoinColumn(name="media_category_id", referencedColumnName="id")},
     *       inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")})                         
     */
    private $mediaId;

    public function __construct()
    {
        $this->mediaId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMediaId(): Collection
    {
        return $this->mediaId;
    }

    public function addMediaId(Media $mediaId): self
    {
        if (!$this->mediaId->contains($mediaId)) {
            $this->mediaId[] = $mediaId;
            $mediaId->addMediaCategory($this);
        }

        return $this;
    }

    public function removeMediaId(Media $mediaId): self
    {
        if ($this->mediaId->contains($mediaId)) {
            $this->mediaId->removeElement($mediaId);
            $mediaId->removeMediaCategory($this);
        }

        return $this;
    }

    public function getMedias()
    {
        $medias= $this->mediaId->map(function ($media) {
            return $media->getId();
        })->toArray();

        return  $medias;
    }
}
