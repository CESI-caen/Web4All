<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Créer")]
class Créer
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "Id_utilisateur", referencedColumnName: "Id_utilisateur", nullable: false)]
    private ?Utilisateurs $utilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Offres::class)]
    #[ORM\JoinColumn(name: "Id_offre", referencedColumnName: "Id_offre", nullable: false)]
    private ?Offres $offre = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Entreprises::class)]
    #[ORM\JoinColumn(name: "Id_entreprise", referencedColumnName: "Id_entreprise", nullable: false)]
    private ?Entreprises $entreprise = null;

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateurs $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getOffre(): ?Offres
    {
        return $this->offre;
    }

    public function setOffre(?Offres $offre): static
    {
        $this->offre = $offre;

        return $this;
    }

    public function getEntreprise(): ?Entreprises
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprises $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }
}
