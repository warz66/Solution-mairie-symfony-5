<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActualiteRepository")
 */
class Actualite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $debut_publication;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\GreaterThanOrEqual(propertyPath="debut_publication", message="Cette date doit-être postérieure à la date de début de publication.")
     */
    private $fin_publication;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", inversedBy="actualite", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebutPublication(): ?\DateTimeInterface
    {
        return $this->debut_publication;
    }

    public function setDebutPublication(\DateTimeInterface $debut_publication): self
    {   
        $this->debut_publication = $debut_publication;

        return $this;
    }

    public function getFinPublication(): ?\DateTimeInterface
    {
        return $this->fin_publication;
    }

    public function setFinPublication(?\DateTimeInterface $fin_publication): self
    {
        $this->fin_publication = $fin_publication;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(Publication $publication): self
    {
        $this->publication = $publication;

        return $this;
    }
}
