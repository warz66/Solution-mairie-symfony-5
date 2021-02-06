<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KiosqueObjetRepository")
 * @Vich\Uploadable
 */
class KiosqueObjet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=60, maxMessage="Le titre de la revue ne peut pas faire plus de 60 caractères")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var file|null
     * @Vich\UploadableField(mapping="kiosque_objet", fileNameProperty="url")
     * @Assert\File(maxSize="20M")
     * @ORM\JoinColumn(nullable=false)
     */
    private $urlFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_thumbnail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $parution;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getUrlThumbnail(): ?string
    {
        return $this->url_thumbnail;
    }

    public function setUrlThumbnail(string $url_thumbnail): self
    {
        $this->url_thumbnail = $url_thumbnail;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getParution(): ?\DateTimeInterface
    {
        return $this->parution;
    }

    public function setParution(\DateTimeInterface $parution): self
    {
        $this->parution = $parution;

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
}
