<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HoraireRepository")
 */
class Horaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Jour", inversedBy="horaires")
     */
    private $jour;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etablissement", inversedBy="horaires")
     */
    private $etablissement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ouverture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fermeture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?Jour
    {
        return $this->jour;
    }

    public function setJour(?Jour $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getOuverture(): ?string
    {
        return $this->ouverture;
    }

    public function setOuverture(string $ouverture): self
    {
        $this->ouverture = $ouverture;

        return $this;
    }

    public function getFermeture(): ?string
    {
        return $this->fermeture;
    }

    public function setFermeture(string $fermeture): self
    {
        $this->fermeture = $fermeture;

        return $this;
    }
}
