<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RessourceRepository")
 * @Vich\Uploadable
 */
class Ressource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @var file|null
     * @Vich\UploadableField(mapping="ressource", fileNameProperty="url")
     * @Assert\File(maxSize="10M")
     * @ORM\JoinColumn(nullable=false)
     */
    private $urlFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication", inversedBy="ressources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Length(max=80, maxMessage="Le titre de la ressource ne peut pas faire plus de 80 caractères")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * Permet de contraindre la validation sur le mimetype
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {   
        if(null !== $this->urlFile) {
            if (! in_array($this->urlFile->getMimeType(), [
                'application/pdf'
            ])) {
                $context
                    ->buildViolation('Type de fichier non valide (pdf seulement)')
                    ->atPath('urlFile')
                    ->addViolation()
                ;
            }
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): self
    {
        $this->publication = $publication;

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

    /**
     * Get the value of urlFile
     *
     * @return  file|null
     */ 
    public function getUrlFile()
    {
        return $this->urlFile;
    }

    /**
     * Set the value of urlFile
     *
     * @param  file|null  $urlFile
     *
     * @return  self
     */ 
    public function setUrlFile($urlFile)
    {
        $this->urlFile = $urlFile;
        if ($this->urlFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');    
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
}
