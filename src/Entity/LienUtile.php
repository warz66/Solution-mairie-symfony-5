<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LienUtileRepository")
 */
class LienUtile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255, maxMessage="Le lien ne peut pas faire plus de 255 caractères")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication", inversedBy="liens_utiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=80, maxMessage="Le titre du lien ne peut pas faire plus de 80 caractères")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $title;

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
