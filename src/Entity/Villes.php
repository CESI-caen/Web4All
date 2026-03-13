<?php

namespace App\Entity;

use App\Repository\VillesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VillesRepository::class)]
#[ORM\Table(name: "Villes")]
class Villes
{
    #[ORM\Id]
    #[ORM\Column(name: "Id_ville", length: 50)]
    private ?string $id = null;

    #[ORM\Column(name: "Nom_ville", length: 50)]
    private ?string $nomVille = null;

    #[ORM\Column(name: "CP", type: "integer")]
    private ?int $cp = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNomVille(): ?string
    {
        return $this->nomVille;
    }

    public function setNomVille(string $nomVille): static
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): static
    {
        $this->cp = $cp;

        return $this;
    }
}
