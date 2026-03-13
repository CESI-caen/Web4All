<?php

namespace App\Entity;

use App\Repository\EntreprisesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntreprisesRepository::class)]
#[ORM\Table(name: "Entreprises")]
class Entreprises
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_entreprise", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "Nom_Entreprise", length: 50)]
    private ?string $nomEntreprise = null;

    #[ORM\Column(name: "Domaine", length: 50)]
    private ?string $domaine = null;

    #[ORM\Column(name: "Email_RH", length: 50)]
    private ?string $emailRh = null;

    #[ORM\Column(name: "Telephone", length: 50)]
    private ?string $telephone = null;

    #[ORM\Column(name: "Descriptif", length: 50)]
    private ?string $descriptif = null;

    #[ORM\ManyToOne(targetEntity: Villes::class)]
    #[ORM\JoinColumn(name: "Id_ville", referencedColumnName: "Id_ville", nullable: false)]
    private ?Villes $ville = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): static
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getEmailRh(): ?string
    {
        return $this->emailRh;
    }

    public function setEmailRh(string $emailRh): static
    {
        $this->emailRh = $emailRh;

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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): static
    {
        $this->descriptif = $descriptif;

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
}
