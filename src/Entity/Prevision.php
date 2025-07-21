<?php

namespace App\Entity;

use App\Repository\PrevisionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrevisionRepository::class)]
class Prevision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_prevision = null;

    #[ORM\OneToOne(inversedBy: 'prevision')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_match')]
    private ?Matchs $match = null;

    #[ORM\Column]
    private ?float $probabilite_victoire_equipe1 = null;

    #[ORM\Column]
    private ?float $probabilite_victoire_equipe2 = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id_prevision;
    }

    public function getMatch(): ?Matchs
    {
        return $this->match;
    }

    public function setMatch(?Matchs $match): static
    {
        $this->match = $match;

        return $this;
    }

    public function getProbabiliteVictoireEquipe1(): ?float
    {
        return $this->probabilite_victoire_equipe1;
    }

    public function setProbabiliteVictoireEquipe1(float $probabilite_victoire_equipe1): static
    {
        $this->probabilite_victoire_equipe1 = $probabilite_victoire_equipe1;

        return $this;
    }

    public function getProbabiliteVictoireEquipe2(): ?float
    {
        return $this->probabilite_victoire_equipe2;
    }

    public function setProbabiliteVictoireEquipe2(float $probabilite_victoire_equipe2): static
    {
        $this->probabilite_victoire_equipe2 = $probabilite_victoire_equipe2;

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
