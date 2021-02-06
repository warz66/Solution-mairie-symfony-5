<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository") 
 */
class Menu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique2() and value != this.getRubrique3() and value != this.getRubrique4() and value != this.getRubrique5() and value != this.getRubrique6() and value != this.getRubrique7() and value != this.getRubrique8() or value == null", message="Attention ce champ doit être unique")                     
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique1;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique1() and value != this.getRubrique3() and value != this.getRubrique4() and value != this.getRubrique5() and value != this.getRubrique6() and value != this.getRubrique7() and value != this.getRubrique8() or value == null", message="Attention ce champ doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique2;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique2() and value != this.getRubrique1() and value != this.getRubrique4() and value != this.getRubrique5() and value != this.getRubrique6() and value != this.getRubrique7() and value != this.getRubrique8() or value == null", message="Attention ce champ doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique3;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique2() and value != this.getRubrique3() and value != this.getRubrique1() and value != this.getRubrique5() and value != this.getRubrique6() and value != this.getRubrique7() and value != this.getRubrique8() or value == null", message="Attention ce champ doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique4;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique2() and value != this.getRubrique3() and value != this.getRubrique4() and value != this.getRubrique1() and value != this.getRubrique6() and value != this.getRubrique7() and value != this.getRubrique8() or value == null", message="Attention ce champ doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique5;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique2() and value != this.getRubrique3() and value != this.getRubrique4() and value != this.getRubrique5() and value != this.getRubrique1() and value != this.getRubrique7() and value != this.getRubrique8() or value == null", message="Attention ce champ doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique6;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique2() and value != this.getRubrique3() and value != this.getRubrique4() and value != this.getRubrique5() and value != this.getRubrique6() and value != this.getRubrique1() and value != this.getRubrique8() or value == null", message="Attention ce champ doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique7;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Publication", cascade={"persist", "remove"})
     * @Assert\Expression("value != this.getRubrique2() and value != this.getRubrique3() and value != this.getRubrique4() and value != this.getRubrique5() and value != this.getRubrique6() and value != this.getRubrique7() and value != this.getRubrique1() or value == null", message="Attention ce champ doit être unique")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $rubrique8;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRubrique1(): ?Publication
    {
        return $this->rubrique1;
    }

    public function setRubrique1(?Publication $rubrique1): self
    {
        $this->rubrique1 = $rubrique1;

        return $this;
    }

    public function getRubrique2(): ?Publication
    {
        return $this->rubrique2;
    }

    public function setRubrique2(?Publication $rubrique2): self
    {
        $this->rubrique2 = $rubrique2;

        return $this;
    }

    public function getRubrique3(): ?Publication
    {
        return $this->rubrique3;
    }

    public function setRubrique3(?Publication $rubrique3): self
    {
        $this->rubrique3 = $rubrique3;

        return $this;
    }

    public function getRubrique4(): ?Publication
    {
        return $this->rubrique4;
    }

    public function setRubrique4(?Publication $rubrique4): self
    {
        $this->rubrique4 = $rubrique4;

        return $this;
    }

    public function getRubrique5(): ?Publication
    {
        return $this->rubrique5;
    }

    public function setRubrique5(?Publication $rubrique5): self
    {
        $this->rubrique5 = $rubrique5;

        return $this;
    }

    public function getRubrique6(): ?Publication
    {
        return $this->rubrique6;
    }

    public function setRubrique6(?Publication $rubrique6): self
    {
        $this->rubrique6 = $rubrique6;

        return $this;
    }

    public function getRubrique7(): ?Publication
    {
        return $this->rubrique7;
    }

    public function setRubrique7(?Publication $rubrique7): self
    {
        $this->rubrique7 = $rubrique7;

        return $this;
    }

    public function getRubrique8(): ?Publication
    {
        return $this->rubrique8;
    }

    public function setRubrique8(?Publication $rubrique8): self
    {
        $this->rubrique8 = $rubrique8;

        return $this;
    }

    /**
     * Permet de récuperer toutes les rubriques
     */
    public function getAllRubriques()
    {
          $Rubriques[] = $this->rubrique1;
          $Rubriques[] = $this->rubrique2;
          $Rubriques[] = $this->rubrique3;
          $Rubriques[] = $this->rubrique4;
          $Rubriques[] = $this->rubrique5;
          $Rubriques[] = $this->rubrique6;
          $Rubriques[] = $this->rubrique7;
          $Rubriques[] = $this->rubrique8;

          return $Rubriques;
    }

}
