<?php

namespace App\Entity;

use App\Repository\ResultatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatRepository::class)]
class Resultat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_resultat = null;

    #[ORM\OneToOne(inversedBy: 'resultat')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_match')]
    private ?Matchs $match = null;

    #[ORM\Column]
    private ?int $score_equipe1 = null;

    #[ORM\Column]
    private ?int $score_equipe2 = null;

    #[ORM\Column]
    private ?int $fautes_equipe1 = null;

    #[ORM\Column]
    private ?int $fautes_equipe2 = null;

    #[ORM\Column]
    private ?int $cartons_jaunes_equipe1 = null;

    #[ORM\Column]
    private ?int $cartons_jaunes_equipe2 = null;

    #[ORM\Column]
    private ?int $cartons_rouges_equipe1 = null;

    #[ORM\Column]
    private ?int $cartons_rouges_equipe2 = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id_resultat;
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

    public function getScoreEquipe1(): ?int
    {
        return $this->score_equipe1;
    }

    public function setScoreEquipe1(int $score_equipe1): static
    {
        $this->score_equipe1 = $score_equipe1;

        return $this;
    }

    public function getScoreEquipe2(): ?int
    {
        return $this->score_equipe2;
    }

    public function setScoreEquipe2(int $score_equipe2): static
    {
        $this->score_equipe2 = $score_equipe2;

        return $this;
    }

    public function getFautesEquipe1(): ?int
    {
        return $this->fautes_equipe1;
    }

    public function setFautesEquipe1(int $fautes_equipe1): static
    {
        $this->fautes_equipe1 = $fautes_equipe1;

        return $this;
    }

    public function getFautesEquipe2(): ?int
    {
        return $this->fautes_equipe2;
    }

    public function setFautesEquipe2(int $fautes_equipe2): static
    {
        $this->fautes_equipe2 = $fautes_equipe2;

        return $this;
    }

    public function getCartonsJaunesEquipe1(): ?int
    {
        return $this->cartons_jaunes_equipe1;
    }

    public function setCartonsJaunesEquipe1(int $cartons_jaunes_equipe1): static
    {
        $this->cartons_jaunes_equipe1 = $cartons_jaunes_equipe1;

        return $this;
    }

    public function getCartonsJaunesEquipe2(): ?int
    {
        return $this->cartons_jaunes_equipe2;
    }

    public function setCartonsJaunesEquipe2(int $cartons_jaunes_equipe2): static
    {
        $this->cartons_jaunes_equipe2 = $cartons_jaunes_equipe2;

        return $this;
    }

    public function getCartonsRougesEquipe1(): ?int
    {
        return $this->cartons_rouges_equipe1;
    }

    public function setCartonsRougesEquipe1(int $cartons_rouges_equipe1): static
    {
        $this->cartons_rouges_equipe1 = $cartons_rouges_equipe1;

        return $this;
    }

    public function getCartonsRougesEquipe2(): ?int
    {
        return $this->cartons_rouges_equipe2;
    }

    public function setCartonsRougesEquipe2(int $cartons_rouges_equipe2): static
    {
        $this->cartons_rouges_equipe2 = $cartons_rouges_equipe2;

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
