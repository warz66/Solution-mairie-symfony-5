<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccesRapideObjetRepository")
 */
class AccesRapideObjet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=30, maxMessage="Le titre ne peut pas faire plus de {{ limit }} caractères")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $icone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Assert\NotNull
     */
    private $lien_publication;

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

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): self
    {
        $this->icone = $icone;

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
