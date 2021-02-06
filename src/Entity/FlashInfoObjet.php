<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FlashInfoObjetRepository")
 */
class FlashInfoObjet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=60, maxMessage="Le titre ne peut pas faire plus de 60 caractères")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Assert\NotNull
     */
    private $lien_interne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien_externe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=180, maxMessage="La description ne peut pas faire plus de 180 caractères")
     */
    private $information;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $choix_lien;

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

    public function getLienInterne(): ?Publication
    {
        return $this->lien_interne;
    }

    public function setLienInterne(?Publication $lien_interne): self
    {
        $this->lien_interne = $lien_interne;

        return $this;
    }

    public function getLienExterne(): ?string
    {
        return $this->lien_externe;
    }

    public function setLienExterne(?string $lien_externe): self
    {
        $this->lien_externe = $lien_externe;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getChoixLien(): ?bool
    {
        return $this->choix_lien;
    }

    public function setChoixLien(?bool $choix_lien): self
    {
        $this->choix_lien = $choix_lien;

        return $this;
    }
}
