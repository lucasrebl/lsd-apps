<?php

namespace App\Entity;

use App\Repository\MatchEquipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchEquipeRepository::class)]
class MatchEquipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_match_equipe = null;

    #[ORM\ManyToOne(inversedBy: 'matchEquipes')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_match')]
    private ?Matchs $id_match = null;

    #[ORM\ManyToOne(inversedBy: 'matchEquipes')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_equipe')]
    private ?Equipe $id_equipe = null;

    #[ORM\Column(length: 10)]
    private ?string $role = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    public function getId(): ?int
    {
        return $this->id_match_equipe;
    }

    public function getIdMatch(): ?Matchs
    {
        return $this->id_match;
    }

    public function setIdMatch(?Matchs $id_match): static
    {
        $this->id_match = $id_match;

        return $this;
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
