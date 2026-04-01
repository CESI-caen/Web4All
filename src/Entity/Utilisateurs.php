<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
#[ORM\Table(name: "Utilisateurs")]
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_utilisateur", type: Types::BIGINT)]
    private ?int $idUtilisateur = null;

    #[ORM\Column(name: "Nom", type: Types::STRING, length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(name: "Prenom", type: Types::STRING, length: 50, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(name: "Email", type: Types::STRING, length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: "Mdp", type: Types::STRING, length: 50, nullable: true)]
    private ?string $mdp = null;

    #[ORM\Column(name: "Telephone", type: Types::STRING, length: 17, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(name: "Cv", type: Types::BOOLEAN, nullable: true)]
    private ?bool $cv = null;

    #[ORM\Column(name: "Lettre_motivation", type: Types::BOOLEAN, nullable: true)]
    private ?bool $lettreMotivation = null;

    #[ORM\Column(name: "Id_session", type: Types::BIGINT)]
    private ?int $idSession = null;

    #[ORM\Column(name: "Id_ville", type: Types::BIGINT)]
    private ?int $idVille = null;

    #[ORM\Column(name: "Id_type_compte", type: Types::BIGINT)]
    private ?int $idTypeCompte = null;

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
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

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): static
    {
        $this->mdp = $mdp;
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

    public function isCv(): ?bool
    {
        return $this->cv;
    }

    public function setCv(?bool $cv): static
    {
        $this->cv = $cv;
        return $this;
    }

    public function isLettreMotivation(): ?bool
    {
        return $this->lettreMotivation;
    }

    public function setLettreMotivation(?bool $lettreMotivation): static
    {
        $this->lettreMotivation = $lettreMotivation;
        return $this;
    }

    public function getIdSession(): ?int
    {
        return $this->idSession;
    }

    public function setIdSession(int $idSession): static
    {
        $this->idSession = $idSession;
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

    public function getIdTypeCompte(): ?int
    {
        return $this->idTypeCompte;
    }

    public function setIdTypeCompte(int $idTypeCompte): static
    {
        $this->idTypeCompte = $idTypeCompte;
        return $this;
    }
}
