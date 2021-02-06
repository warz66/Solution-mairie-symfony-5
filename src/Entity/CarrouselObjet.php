<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarrouselObjetRepository")
 * @Vich\Uploadable
 */
class CarrouselObjet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $introduction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cover_image;

    /**
     * @var file|null
     * @Vich\UploadableField(mapping="cover_image_carrousel", fileNameProperty="cover_image")
     * @Assert\File(maxSize="1M", mimeTypes = {"image/jpeg", "image/png"})
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $lien_publication;

    /**
     * Permet de contraindre la validation si une image de couverture et le lien de publication sont null
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {   
        if (!isset($this->cover_image) && !isset($this->imageFile) && !isset($this->lien_publication)) {
            $context->buildViolation('Veuillez choisir un lien vers une publication et/ou une image de présentation.')
                ->atPath('imageFile')
                ->addViolation();
            $context->buildViolation('Veuillez choisir un lien vers une publication et/ou une image de présentation.')
                ->atPath('lien_publication')
                ->addViolation();
        }
    }

    /**
     * Get the value of imageFile
     *
     * @return  file|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param  file|null  $imageFile
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');    
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    public function setCoverImage(?string $cover_image): self
    {
        $this->cover_image = $cover_image;

        return $this;
    }

    public function getLienPublication(): ?Publication
    {
        return $this->lien_publication;
    }

    public function setLienPublication(?Publication $lien_publication): self
    {
        $this->lien_publication = $lien_publication;

        return $this;
    }
}
