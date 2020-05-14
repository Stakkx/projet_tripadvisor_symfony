<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtablissementRepository")
 */
class Etablissement
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
     * @ORM\Column(type="text")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="etablissements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="etablissement")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="etablissement")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Horaire", mappedBy="etablissement")
     */
    private $horaires;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Type", inversedBy="etablissements")
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lienMap;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Horaire2", mappedBy="etablissement")
     */
    private $horaire2s;

     /**
     * @var string
     */
    public $q = '';

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->horaires = new ArrayCollection();
        $this->type = new ArrayCollection();
        $this->horaire2s = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setEtablissement($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getEtablissement() === $this) {
                $photo->setEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setEtablissement($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getEtablissement() === $this) {
                $commentaire->setEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Horaire[]
     */
    public function getHoraires(): Collection
    {
        return $this->horaires;
    }

    public function addHoraire(Horaire $horaire): self
    {
        if (!$this->horaires->contains($horaire)) {
            $this->horaires[] = $horaire;
            $horaire->setEtablissement($this);
        }

        return $this;
    }

    public function removeHoraire(Horaire $horaire): self
    {
        if ($this->horaires->contains($horaire)) {
            $this->horaires->removeElement($horaire);
            // set the owning side to null (unless already changed)
            if ($horaire->getEtablissement() === $this) {
                $horaire->setEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Type $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type[] = $type;
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->type->contains($type)) {
            $this->type->removeElement($type);
        }

        return $this;
    }

    public function getLienMap(): ?string
    {
        return $this->lienMap;
    }

    public function setLienMap(?string $lienMap): self
    {
        $this->lienMap = $lienMap;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Horaire2[]
     */
    public function getHoraire2s(): Collection
    {
        return $this->horaire2s;
    }

    public function addHoraire2(Horaire2 $horaire2): self
    {
        if (!$this->horaire2s->contains($horaire2)) {
            $this->horaire2s[] = $horaire2;
            $horaire2->setEtablissement($this);
        }

        return $this;
    }

    public function removeHoraire2(Horaire2 $horaire2): self
    {
        if ($this->horaire2s->contains($horaire2)) {
            $this->horaire2s->removeElement($horaire2);
            // set the owning side to null (unless already changed)
            if ($horaire2->getEtablissement() === $this) {
                $horaire2->setEtablissement(null);
            }
        }

        return $this;
    }


}
