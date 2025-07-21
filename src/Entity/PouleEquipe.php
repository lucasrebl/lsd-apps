<?php

namespace App\Entity;

use App\Repository\PouleEquipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PouleEquipeRepository::class)]
class PouleEquipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_poule_equipe = null;

    #[ORM\ManyToOne(inversedBy: 'pouleEquipes')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_equipe')]
    private ?Equipe $id_equipe = null;

    #[ORM\ManyToOne(inversedBy: 'pouleEquipes')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_poule')]
    private ?Poule $id_poule = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id_poule_equipe;
    }

    public function getIdEquipe(): ?Equipe
    {
        return $this->id_equipe;
    }

    public function setIdEquipe(?Equipe $id_equipe): static
    {
        $this->id_equipe = $id_equipe;

        return $this;
    }

    public function getIdPoule(): ?Poule
    {
        return $this->id_poule;
    }

    public function setIdPoule(?Poule $id_poule): static
    {
        $this->id_poule = $id_poule;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTime $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }
}
