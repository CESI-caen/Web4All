<?php

namespace App\Entity;

use App\Repository\EntreprisesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntreprisesRepository::class)]
#[ORM\Table(name: "Entreprises")]
class Entreprises
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_entreprise", type: Types::BIGINT)]
    private ?int $idEntreprise = null;

    #[ORM\Column(name: "Nom", type: Types::STRING, length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(name: "Email", type: Types::STRING, length: 50, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: "Telephone", type: Types::STRING, length: 50, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(name: "Descriptif", type: Types::STRING, length: 50, nullable: true)]
    private ?string $descriptif = null;

    #[ORM\Column(name: "Id_ville", type: Types::BIGINT)]
    private ?int $idVille = null;

    #[ORM\Column(name: "Id_utilisateur", type: Types::BIGINT)]
    private ?int $idUtilisateur = null;

    public function getIdEntreprise(): ?int
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(int $idEntreprise): static
    {
        $this->idEntreprise = $idEntreprise;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
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

    public function getIdVille(): ?int
    {
        return $this->idVille;
    }

    public function setIdVille(int $idVille): static
    {
        $this->idVille = $idVille;

        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }
}
