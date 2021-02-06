<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 */
class Evenement
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
    private $debut_evenement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\GreaterThanOrEqual(propertyPath="debut_evenement", message="La date de fin de l'événement ne peut être antérieure à son commencement.")
     */
    private $fin_evenement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", inversedBy="evenement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subtitle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebutEvenement(): ?\DateTimeInterface
    {
        return $this->debut_evenement;
    }

    public function setDebutEvenement(\DateTimeInterface $debut_evenement): self
    {
        $this->debut_evenement = $debut_evenement;

        return $this;
    }

    public function getFinEvenement(): ?\DateTimeInterface
    {
        return $this->fin_evenement;
    }

    public function setFinEvenement(?\DateTimeInterface $fin_evenement): self
    {
        $this->fin_evenement = $fin_evenement;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }
}
