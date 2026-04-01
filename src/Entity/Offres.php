<?php

namespace App\Entity;

use App\Repository\OffresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffresRepository::class)]
#[ORM\Table(name: "Offres")]
class Offres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_offre", type: Types::BIGINT)]
    private ?int $idOffre = null;

    #[ORM\Column(name: "Descriptif", type: Types::TEXT, nullable: true)]
    private ?string $descriptif = null;

    #[ORM\Column(name: "Date_debut", type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: "Date_fin", type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(name: "Duree", type: Types::STRING, length: 50, nullable: true)]
    private ?string $duree = null;

    #[ORM\Column(name: "Renumeration", type: Types::STRING, length: 50, nullable: true)]
    private ?string $renumeration = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "Id_entreprise", referencedColumnName: "Id_entreprise")]
    private ?Entreprises $entreprise = null;

    public function getIdOffre(): ?int
    {
        return $this->idOffre;
    }

    public function setIdOffre(int $idOffre): static
    {
        $this->idOffre = $idOffre;

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

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

    public function getRenumeration(): ?string
    {
        return $this->renumeration;
    }

    public function setRenumeration(?string $renumeration): static
    {
        $this->renumeration = $renumeration;

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
