<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RubriqueRepository")
 */
class Rubrique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $category = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", inversedBy="rubrique", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?array
    {
        return $this->category;
    }

    public function setCategory(?array $category): self
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
