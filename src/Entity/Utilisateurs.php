<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
#[ORM\Table(name: "Utilisateurs")]
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_utilisateur", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Nom", length: 50)]
    private ?string $nom = null;

    #[ORM\Column(name: "Prenom", length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(name: "Email", length: 100)]
    private ?string $email = null;

    #[ORM\Column(name: "mot_de_passe", length: 50)]
    private ?string $motDePasse = null;

    #[ORM\Column(name: "Telephone", length: 17)]
    private ?string $telephone = null;

    #[ORM\ManyToOne(targetEntity: Villes::class)]
    #[ORM\JoinColumn(name: "Id_ville", referencedColumnName: "Id_ville", nullable: false)]
    private ?Villes $ville = null;

    #[ORM\ManyToOne(targetEntity: Types::class)]
    #[ORM\JoinColumn(name: "Id_type", referencedColumnName: "Id_type", nullable: false)]
    private ?Types $type = null;

    #[ORM\ManyToMany(targetEntity: Offres::class, inversedBy: "commenters")]
    #[ORM\JoinTable(name: "Commenter")]
    #[ORM\JoinColumn(name: "Id_utilisateur", referencedColumnName: "Id_utilisateur")]
    #[ORM\InverseJoinColumn(name: "Id_offre", referencedColumnName: "Id_offre")]
    private Collection $offresCommentees;

    #[ORM\ManyToMany(targetEntity: Offres::class, inversedBy: "postulants")]
    #[ORM\JoinTable(name: "Postuler")]
    #[ORM\JoinColumn(name: "Id_utilisateur", referencedColumnName: "Id_utilisateur")]
    #[ORM\InverseJoinColumn(name: "Id_offre", referencedColumnName: "Id_offre")]
    private Collection $offresPostulees;

    #[ORM\ManyToMany(targetEntity: Offres::class, inversedBy: "souhaiteesPar")]
    #[ORM\JoinTable(name: "Vouloir")]
    #[ORM\JoinColumn(name: "Id_utilisateur", referencedColumnName: "Id_utilisateur")]
    #[ORM\InverseJoinColumn(name: "Id_offre", referencedColumnName: "Id_offre")]
    private Collection $offresSouhaitees;

    #[ORM\ManyToMany(targetEntity: Offres::class, inversedBy: "createurs")]
    #[ORM\JoinTable(name: "Créer")]
    #[ORM\JoinColumn(name: "Id_utilisateur", referencedColumnName: "Id_utilisateur")]
    #[ORM\InverseJoinColumn(name: "Id_offre", referencedColumnName: "Id_offre")]
    private Collection $offresCrees;

    public function __construct()
    {
        $this->offresCommentees = new ArrayCollection();
        $this->offresPostulees = new ArrayCollection();
        $this->offresSouhaitees = new ArrayCollection();
        $this->offresCrees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getType(): ?Types
    {
        return $this->type;
    }

    public function setType(?Types $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Offres>|
     */
    public function getOffresCommentees(): Collection
    {
        return $this->offresCommentees;
    }

    public function addOffresCommentee(Offres $offresCommentee): static
    {
        if (!$this->offresCommentees->contains($offresCommentee)) {
            $this->offresCommentees->add($offresCommentee);
        }

        return $this;
    }

    public function removeOffresCommentee(Offres $offresCommentee): static
    {
        $this->offresCommentees->removeElement($offresCommentee);

        return $this;
    }

    /**
     * @return Collection<int, Offres>|
     */
    public function getOffresPostulees(): Collection
    {
        return $this->offresPostulees;
    }

    public function addOffresPostulee(Offres $offresPostulee): static
    {
        if (!$this->offresPostulees->contains($offresPostulee)) {
            $this->offresPostulees->add($offresPostulee);
        }

        return $this;
    }

    public function removeOffresPostulee(Offres $offresPostulee): static
    {
        if ($this->offresPostulees->removeElement($offresPostulee)) {
            $offresPostulee->removePostulant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Offres>|
     */
    public function getOffresSouhaitees(): Collection
    {
        return $this->offresSouhaitees;
    }

    public function addOffresSouhaitee(Offres $offresSouhaitee): static
    {
        if (!$this->offresSouhaitees->contains($offresSouhaitee)) {
            $this->offresSouhaitees->add($offresSouhaitee);
        }

        return $this;
    }

    public function removeOffresSouhaitee(Offres $offresSouhaitee): static
    {
        if ($this->offresSouhaitees->removeElement($offresSouhaitee)) {
            $offresSouhaitee->removeSouhaiteesPar($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Offres>|
     */
    public function getOffresCrees(): Collection
    {
        return $this->offresCrees;
    }

    public function addOffresCree(Offres $offresCree): static
    {
        if (!$this->offresCrees->contains($offresCree)) {
            $this->offresCrees->add($offresCree);
        }

        return $this;
    }

    public function removeOffresCree(Offres $offresCree): static
    {
        if ($this->offresCrees->removeElement($offresCree)) {
            $offresCree->removeCreateur($this);
        }

        return $this;
    }
}
