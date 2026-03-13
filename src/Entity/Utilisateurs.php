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

    #[ORM\Column(name: "Nom_utilisateurs", length: 50)]
    private ?string $nomUtilisateurs = null;

    #[ORM\Column(name: "Prenom_utilisateurs", length: 50)]
    private ?string $prenomUtilisateurs = null;

    #[ORM\Column(name: "Email_utilisateurs", length: 100)]
    private ?string $emailUtilisateurs = null;

    #[ORM\Column(name: "mot_de_passe", length: 50)]
    private ?string $motDePasse = null;

    #[ORM\Column(name: "Telephone_utilisateurs", length: 20)]
    private ?string $telephoneUtilisateurs = null;

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

    public function getNomUtilisateurs(): ?string
    {
        return $this->nomUtilisateurs;
    }

    public function setNomUtilisateurs(string $nomUtilisateurs): static
    {
        $this->nomUtilisateurs = $nomUtilisateurs;

        return $this;
    }

    public function getPrenomUtilisateurs(): ?string
    {
        return $this->prenomUtilisateurs;
    }

    public function setPrenomUtilisateurs(string $prenomUtilisateurs): static
    {
        $this->prenomUtilisateurs = $prenomUtilisateurs;

        return $this;
    }

    public function getEmailUtilisateurs(): ?string
    {
        return $this->emailUtilisateurs;
    }

    public function setEmailUtilisateurs(string $emailUtilisateurs): static
    {
        $this->emailUtilisateurs = $emailUtilisateurs;

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

    public function getTelephoneUtilisateurs(): ?string
    {
        return $this->telephoneUtilisateurs;
    }

    public function setTelephoneUtilisateurs(string $telephoneUtilisateurs): static
    {
        $this->telephoneUtilisateurs = $telephoneUtilisateurs;

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
        $this->offresPostulees->removeElement($offresPostulee);

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
        $this->offresSouhaitees->removeElement($offresSouhaitee);

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
        $this->offresCrees->removeElement($offresCree);

        return $this;
    }
}
