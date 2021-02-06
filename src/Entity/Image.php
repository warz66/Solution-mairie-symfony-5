<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
    private $caption;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication", inversedBy="images")
     */
    private $publication;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Galerie", inversedBy="images")
     */
    private $galerie;

    private $source_path;

    /**
     * Undocumented function
     *
     * @ORM\PostPersist
     * 
     */
    public function saveFileImgOnServer()
    {
        $target_path = getcwd() . $this->url;
        move_uploaded_file($this->source_path, $target_path);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
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

    public function getGalerie(): ?Galerie
    {
        return $this->galerie;
    }

    public function setGalerie(?Galerie $galerie): self
    {
        $this->galerie = $galerie;

        return $this;
    }

    public function getSource_path()
    {
        return $this->source_path;
    }

    public function setSource_path($source_path)
    {
        $this->source_path = $source_path;

        return $this;
    }
}
