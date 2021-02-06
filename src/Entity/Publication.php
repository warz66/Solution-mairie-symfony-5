<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicationRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @UniqueEntity(fields={"title"}, message="Une autre publication possède déjà ce titre, merci de le modifier")
 */
class Publication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=100, maxMessage="Le titre ne peut dépasser {{ limit }} caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(max=500, maxMessage="L'introduction ne peut dépasser {{ limit }} caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cover_image;

    /**
     * @var file|null
     * @Vich\UploadableField(mapping="cover_image_publication", fileNameProperty="cover_image")
     * @Assert\File(maxSize="1M", mimeTypes = {"image/jpeg", "image/png"})
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="publication", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="publications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LienUtile", mappedBy="publication", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private $liens_utiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ressource", mappedBy="publication", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private $ressources;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication", inversedBy="enfants")
     * @ORM\joinColumn(onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Publication", mappedBy="parent") //, fetch="EAGER"
     */
    private $enfants;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Actualite", mappedBy="publication", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $actualite;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Evenement", mappedBy="publication", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $evenement;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infos_pratiques;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LienUtileInterne", mappedBy="publication", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private $liens_utiles_internes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LienUtileExterne", mappedBy="publication", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private $liens_utiles_externes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Galerie", inversedBy="publications")
     */
    private $galeries;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Rubrique", mappedBy="publication", cascade={"persist", "remove"})
     */
    private $rubrique;

    /**
     * @ORM\Column(type="boolean")
     */
    private $trash = 0;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->id_publication = $this->id;
        $this->liens_utiles = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->enfants = new ArrayCollection();
        $this->liens_utiles_internes = new ArrayCollection();
        $this->liens_utiles_externes = new ArrayCollection();
        $this->galeries = new ArrayCollection();
    }

    /**
     * Permet de contraindre la validation si une image de couverture n'a pas été ajouté 
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (!isset($this->cover_image) && !isset($this->imageFile)) {
            $context->buildViolation('Veuillez ajouter une image de couverture')
                ->atPath('imageFile')
                ->addViolation();
        }
    }

    /**
     * Permet de contraindre les liens utiles internes si ils ne sont pas uniques
     * @Assert\Callback
     */
    public function validateUniqueLiensUtilesInternes(ExecutionContextInterface $context)
    {   
        //dd($this->liens_utiles_internes->toArray());
        $error = false;
        foreach($this->liens_utiles_internes->toArray() as $lien) {
            foreach($this->liens_utiles_internes->toArray() as $lien2) {
                if ($lien->getLienPublication() === $lien2->getLienPublication() && $lien !== $lien2) {
                    $error = true;
                    break 2;
                }
            }
        }
        if ($error) {
            $context->buildViolation('Veuillez vérifier que chaque lien soit unique')
                ->atPath('liens_utiles_internes')
                ->addViolation();
        }
    }

    /**
     * Permet d'initiliser le slug ! 
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    public function setCoverImage(?string $cover_image): self
    {
        $this->cover_image = $cover_image;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPublication($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getPublication() === $this) {
                $image->setPublication(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of imageFile
     *
     * @return  file|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param  file|null  $imageFile
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');    
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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

    /**
     * @return Collection|LienUtile[]
     */
    public function getLiensUtiles(): Collection
    {
        return $this->liens_utiles;
    }

    public function addLiensUtile(LienUtile $liensUtile): self
    {
        if (!$this->liens_utiles->contains($liensUtile)) {
            $this->liens_utiles[] = $liensUtile;
            $liensUtile->setPublication($this);
        }

        return $this;
    }

    public function removeLiensUtile(LienUtile $liensUtile): self
    {
        if ($this->liens_utiles->contains($liensUtile)) {
            $this->liens_utiles->removeElement($liensUtile);
            // set the owning side to null (unless already changed)
            if ($liensUtile->getPublication() === $this) {
                $liensUtile->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setPublication($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getPublication() === $this) {
                $ressource->setPublication(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getEnfants(): Collection
    {
        return $this->enfants;
    }

    public function addEnfant(self $enfant): self
    {
        if (!$this->enfants->contains($enfant)) {
            $this->enfants[] = $enfant;
            $enfant->setParent($this);
        }

        return $this;
    }

    public function removeEnfant(self $enfant): self
    {
        if ($this->enfants->contains($enfant)) {
            $this->enfants->removeElement($enfant);
            // set the owning side to null (unless already changed)
            if ($enfant->getParent() === $this) {
                $enfant->setParent(null);
            }
        }

        return $this;
    }

    public function getActualite(): ?Actualite
    {
        return $this->actualite;
    }

    public function setActualite(Actualite $actualite): self
    {
        $this->actualite = $actualite;

        // set the owning side of the relation if necessary
        if ($actualite->getPublication() !== $this) {
            $actualite->setPublication($this);
        }

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(Evenement $evenement): self
    {
        $this->evenement = $evenement;

        // set the owning side of the relation if necessary
        if ($evenement->getPublication() !== $this) {
            $evenement->setPublication($this);
        }

        return $this;
    }

    public function getInfosPratiques(): ?string
    {
        return $this->infos_pratiques;
    }

    public function setInfosPratiques(?string $infos_pratiques): self
    {
        $this->infos_pratiques = $infos_pratiques;

        return $this;
    }

    /**
     * @return Collection|LienUtileInterne[]
     */
    public function getLiensUtilesInternes(): Collection
    {
        return $this->liens_utiles_internes;
    }

    public function addLiensUtilesInterne(LienUtileInterne $liensUtilesInterne): self
    {
        if (!$this->liens_utiles_internes->contains($liensUtilesInterne)) {
            $this->liens_utiles_internes[] = $liensUtilesInterne;
            $liensUtilesInterne->setPublication($this);
        }

        return $this;
    }

    public function removeLiensUtilesInterne(LienUtileInterne $liensUtilesInterne): self
    {
        if ($this->liens_utiles_internes->contains($liensUtilesInterne)) {
            $this->liens_utiles_internes->removeElement($liensUtilesInterne);
            // set the owning side to null (unless already changed)
            if ($liensUtilesInterne->getPublication() === $this) {
                $liensUtilesInterne->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LienUtileExterne[]
     */
    public function getLiensUtilesExternes(): Collection
    {
        return $this->liens_utiles_externes;
    }

    public function addLiensUtilesExterne(LienUtileExterne $liensUtilesExterne): self
    {
        if (!$this->liens_utiles_externes->contains($liensUtilesExterne)) {
            $this->liens_utiles_externes[] = $liensUtilesExterne;
            $liensUtilesExterne->setPublication($this);
        }

        return $this;
    }

    public function removeLiensUtilesExterne(LienUtileExterne $liensUtilesExterne): self
    {
        if ($this->liens_utiles_externes->contains($liensUtilesExterne)) {
            $this->liens_utiles_externes->removeElement($liensUtilesExterne);
            // set the owning side to null (unless already changed)
            if ($liensUtilesExterne->getPublication() === $this) {
                $liensUtilesExterne->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Galerie[]
     */
    public function getGaleries(): Collection
    {
        return $this->galeries;
    }

    public function addGalery(Galerie $galery): self
    {
        if (!$this->galeries->contains($galery)) {
            $this->galeries[] = $galery;
        }

        return $this;
    }

    public function removeGalery(Galerie $galery): self
    {
        if ($this->galeries->contains($galery)) {
            $this->galeries->removeElement($galery);
        }

        return $this;
    }

    public function getRubrique(): ?Rubrique
    {
        return $this->rubrique;
    }

    public function setRubrique(Rubrique $rubrique): self
    {
        $this->rubrique = $rubrique;

        // set the owning side of the relation if necessary
        if ($rubrique->getPublication() !== $this) {
            $rubrique->setPublication($this);
        }

        return $this;
    }

    public function getTrash(): ?bool
    {
        return $this->trash;
    }

    public function setTrash(bool $trash): self
    {
        $this->trash = $trash;

        return $this;
    }
}
