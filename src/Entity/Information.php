<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InformationRepository")
 * @UniqueEntity(fields={"nom"})
 */
class Information
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=255, maxMessage="Le titre ne peut dépasser {{ limit }} caractères")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     * @Assert\Length(max=10, maxMessage="Le code postale ne peut dépasser {{ limit }} caractères")
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank
     * @Assert\Length(max=40, maxMessage="Le nom de la ville ne peut dépasser {{ limit }} caractères")
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     * @Assert\Length(max=20, maxMessage="Le n° de téléphone ne peut dépasser {{ limit }} caractères")
     */
    private $telephone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $horaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $complement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReseauSocial", mappedBy="information", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private $reseaux_sociaux;

    public function __construct()
    {
        $this->reseaux_sociaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getHoraire(): ?string
    {
        return $this->horaire;
    }

    public function setHoraire(?string $horaire): self
    {
        $this->horaire = $horaire;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * @return Collection|ReseauSocial[]
     */
    public function getReseauxSociaux(): Collection
    {
        return $this->reseaux_sociaux;
    }

    public function addReseauxSociaux(ReseauSocial $reseauxSociaux): self
    {
        if (!$this->reseaux_sociaux->contains($reseauxSociaux)) {
            $this->reseaux_sociaux[] = $reseauxSociaux;
            $reseauxSociaux->setInformation($this);
        }

        return $this;
    }

    public function removeReseauxSociaux(ReseauSocial $reseauxSociaux): self
    {
        if ($this->reseaux_sociaux->contains($reseauxSociaux)) {
            $this->reseaux_sociaux->removeElement($reseauxSociaux);
            // set the owning side to null (unless already changed)
            if ($reseauxSociaux->getInformation() === $this) {
                $reseauxSociaux->setInformation(null);
            }
        }

        return $this;
    }
}
