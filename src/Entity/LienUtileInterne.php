<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LienUtileInterneRepository")
 */
class LienUtileInterne
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication", inversedBy="liens_utiles_internes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $lien_publication;

    public function getId(): ?int
    {
        return $this->id;
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
