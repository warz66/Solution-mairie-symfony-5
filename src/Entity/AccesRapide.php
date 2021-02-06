<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccesRapideRepository")
 */
class AccesRapide
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement1;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement1() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement2;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement1() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement3;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement1() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement4;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement1() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement5;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement1() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement6;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement1() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement7;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement1() and value != this.getEmplacement9() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement8;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement1() and value != this.getEmplacement10() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement9;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccesRapideObjet", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getEmplacement2() and value != this.getEmplacement3() and value != this.getEmplacement4() and value != this.getEmplacement5() and value != this.getEmplacement6() and value != this.getEmplacement7() and value != this.getEmplacement8() and value != this.getEmplacement9() and value != this.getEmplacement1() or value == null", message="Attention cette emplacement doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $emplacement10;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmplacement1(): ?AccesRapideObjet
    {
        return $this->emplacement1;
    }

    public function setEmplacement1(?AccesRapideObjet $emplacement1): self
    {
        $this->emplacement1 = $emplacement1;

        return $this;
    }

    public function getEmplacement2(): ?AccesRapideObjet
    {
        return $this->emplacement2;
    }

    public function setEmplacement2(?AccesRapideObjet $emplacement2): self
    {
        $this->emplacement2 = $emplacement2;

        return $this;
    }

    public function getEmplacement3(): ?AccesRapideObjet
    {
        return $this->emplacement3;
    }

    public function setEmplacement3(?AccesRapideObjet $emplacement3): self
    {
        $this->emplacement3 = $emplacement3;

        return $this;
    }

    public function getEmplacement4(): ?AccesRapideObjet
    {
        return $this->emplacement4;
    }

    public function setEmplacement4(?AccesRapideObjet $emplacement4): self
    {
        $this->emplacement4 = $emplacement4;

        return $this;
    }

    public function getEmplacement5(): ?AccesRapideObjet
    {
        return $this->emplacement5;
    }

    public function setEmplacement5(?AccesRapideObjet $emplacement5): self
    {
        $this->emplacement5 = $emplacement5;

        return $this;
    }

    public function getEmplacement6(): ?AccesRapideObjet
    {
        return $this->emplacement6;
    }

    public function setEmplacement6(?AccesRapideObjet $emplacement6): self
    {
        $this->emplacement6 = $emplacement6;

        return $this;
    }

    public function getEmplacement7(): ?AccesRapideObjet
    {
        return $this->emplacement7;
    }

    public function setEmplacement7(?AccesRapideObjet $emplacement7): self
    {
        $this->emplacement7 = $emplacement7;

        return $this;
    }

    public function getEmplacement8(): ?AccesRapideObjet
    {
        return $this->emplacement8;
    }

    public function setEmplacement8(?AccesRapideObjet $emplacement8): self
    {
        $this->emplacement8 = $emplacement8;

        return $this;
    }

    public function getEmplacement9(): ?AccesRapideObjet
    {
        return $this->emplacement9;
    }

    public function setEmplacement9(?AccesRapideObjet $emplacement9): self
    {
        $this->emplacement9 = $emplacement9;

        return $this;
    }

    public function getEmplacement10(): ?AccesRapideObjet
    {
        return $this->emplacement10;
    }

    public function setEmplacement10(?AccesRapideObjet $emplacement10): self
    {
        $this->emplacement10 = $emplacement10;

        return $this;
    }

    /**
     * Permet de récuperer chaque emplacement de l'acces rapide
     */
    public function getAllEmplacement()
    {
        $emplacements[] = $this->emplacement1;
        $emplacements[] = $this->emplacement2;
        $emplacements[] = $this->emplacement3;
        $emplacements[] = $this->emplacement4;
        $emplacements[] = $this->emplacement5;
        $emplacements[] = $this->emplacement6;
        $emplacements[] = $this->emplacement7;
        $emplacements[] = $this->emplacement8;
        $emplacements[] = $this->emplacement9;
        $emplacements[] = $this->emplacement10;
        $empty = true;
        foreach($emplacements as $emplacement) {
            if (isset($emplacement)) {
                $empty = false;
                $accesRapide[]  = $emplacement;
            }
        }
        if ($empty) {
            return null;
        } else {
            return $accesRapide;
        }
    }
}
