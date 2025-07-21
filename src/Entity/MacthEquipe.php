<?php

namespace App\Entity;

use App\Repository\MacthEquipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MacthEquipeRepository::class)]
class MacthEquipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_match_equipe = null;

    #[ORM\ManyToOne(inversedBy: 'macthEquipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?matchs $id_match = null;

    #[ORM\ManyToOne(inversedBy: 'macthEquipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?equipe $id_equipe = null;

    #[ORM\Column(length: 10)]
    private ?string $role = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    public function getId(): ?int
    {
        return $this->id_match_equipe;
    }

    public function getIdMatch(): ?matchs
    {
        return $this->id_match;
    }

    public function setIdMatch(?matchs $id_match): static
    {
        $this->id_match = $id_match;

        return $this;
    }

    public function getIdEquipe(): ?equipe
    {
        return $this->id_equipe;
    }

    public function setIdEquipe(?equipe $id_equipe): static
    {
        $this->id_equipe = $id_equipe;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

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
