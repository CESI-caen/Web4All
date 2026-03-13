<?php

namespace App\Entity;

use App\Repository\OffresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffresRepository::class)]
#[ORM\Table(name: "Offres")]
class Offres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_offre", type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: "Descriptif", type: Types::TEXT, nullable: true)]
    private ?string $descriptif = null;

    #[ORM\Column(name: "Debut", type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(name: "Fin", type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fin = null;

    #[ORM\Column(name: "Duree", length: 50, nullable: true)]
    private ?string $duree = null;

    #[ORM\ManyToMany(targetEntity: Utilisateurs::class, mappedBy: "offresCommentees")]
    private Collection $commenters;

    #[ORM\ManyToMany(targetEntity: Utilisateurs::class, mappedBy: "offresPostulees")]
    private Collection $postulants;

    #[ORM\ManyToMany(targetEntity: Utilisateurs::class, mappedBy: "offresSouhaitees")]
    private Collection $souhaiteesPar;

    #[ORM\ManyToMany(targetEntity: Utilisateurs::class, mappedBy: "offresCrees")]
    private Collection $createurs;

    public function __construct()
    {
        $this->commenters = new ArrayCollection();
        $this->postulants = new ArrayCollection();
        $this->souhaiteesPar = new ArrayCollection();
        $this->createurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): static
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(?\DateTimeInterface $debut): static
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): static
    {
        $this->fin = $fin;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateurs>|
     */
    public function getCommenters(): Collection
    {
        return $this->commenters;
    }

    public function addCommenter(Utilisateurs $commenter): static
    {
        if (!$this->commenters->contains($commenter)) {
            $this->commenters->add($commenter);
            $commenter->addOffresCommentee($this);
        }

        return $this;
    }

    public function removeCommenter(Utilisateurs $commenter): static
    {
        if ($this->commenters->removeElement($commenter)) {
            $commenter->removeOffresCommentee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateurs>|
     */
    public function getPostulants(): Collection
    {
        return $this->postulants;
    }

    public function addPostulant(Utilisateurs $postulant): static
    {
        if (!$this->postulants->contains($postulant)) {
            $this->postulants->add($postulant);
            $postulant->addOffresPostulee($this);
        }

        return $this;
    }

    public function removePostulant(Utilisateurs $postulant): static
    {
        if ($this->postulants->removeElement($postulant)) {
            $postulant->removeOffresPostulee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateurs>|
     */
    public function getSouhaiteesPar(): Collection
    {
        return $this->souhaiteesPar;
    }

    public function addSouhaiteesPar(Utilisateurs $souhaiteesPar): static
    {
        if (!$this->souhaiteesPar->contains($souhaiteesPar)) {
            $this->souhaiteesPar->add($souhaiteesPar);
            $souhaiteesPar->addOffresSouhaitee($this);
        }

        return $this;
    }

    public function removeSouhaiteesPar(Utilisateurs $souhaiteesPar): static
    {
        if ($this->souhaiteesPar->removeElement($souhaiteesPar)) {
            $souhaiteesPar->removeOffresSouhaitee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateurs>|
     */
    public function getCreateurs(): Collection
    {
        return $this->createurs;
    }

    public function addCreateur(Utilisateurs $createur): static
    {
        if (!$this->createurs->contains($createur)) {
            $this->createurs->add($createur);
            $createur->addOffresCree($this);
        }

        return $this;
    }

    public function removeCreateur(Utilisateurs $createur): static
    {
        if ($this->createurs->removeElement($createur)) {
            $createur->removeOffresCree($this);
        }

        return $this;
    }
}
