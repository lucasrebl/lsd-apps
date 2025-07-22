<?php

namespace App\Entity;

use App\Repository\StatistiqueEquipeTournoiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatistiqueEquipeTournoiRepository::class)]
class StatistiqueEquipeTournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_stats = null;

    #[ORM\ManyToOne(inversedBy: 'statistiqueEquipeTournois')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_equipe')]
    private ?Equipe $id_equipe = null;

    #[ORM\ManyToOne(inversedBy: 'statistiqueEquipeTournois')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_tournoi')]
    private ?Tournoi $id_tournoi = null;

    #[ORM\Column]
    private ?int $nb_matchs_joués = null;

    #[ORM\Column]
    private ?int $nb_victoires = null;

    #[ORM\Column]
    private ?int $nb_buts_marques = null;

    #[ORM\Column]
    private ?int $nb_buts_encaisses = null;

    #[ORM\Column]
    private ?int $nb_cartons = null;

    #[ORM\Column]
    private ?int $nb_fautes = null;

    public function getId(): ?int
    {
        return $this->id_stats;
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

    public function getIdTournoi(): ?Tournoi
    {
        return $this->id_tournoi;
    }

    public function setIdTournoi(?Tournoi $id_tournoi): static
    {
        $this->id_tournoi = $id_tournoi;

        return $this;
    }

    public function getNbMatchsJoués(): ?int
    {
        return $this->nb_matchs_joués;
    }

    public function setNbMatchsJoués(int $nb_matchs_joués): static
    {
        $this->nb_matchs_joués = $nb_matchs_joués;

        return $this;
    }

    public function getNbVictoires(): ?int
    {
        return $this->nb_victoires;
    }

    public function setNbVictoires(int $nb_victoires): static
    {
        $this->nb_victoires = $nb_victoires;

        return $this;
    }

    public function getNbButsMarques(): ?int
    {
        return $this->nb_buts_marques;
    }

    public function setNbButsMarques(int $nb_buts_marques): static
    {
        $this->nb_buts_marques = $nb_buts_marques;

        return $this;
    }

    public function getNbButsEncaisses(): ?int
    {
        return $this->nb_buts_encaisses;
    }

    public function setNbButsEncaisses(int $nb_buts_encaisses): static
    {
        $this->nb_buts_encaisses = $nb_buts_encaisses;

        return $this;
    }

    public function getNbCartons(): ?int
    {
        return $this->nb_cartons;
    }

    public function setNbCartons(int $nb_cartons): static
    {
        $this->nb_cartons = $nb_cartons;

        return $this;
    }

    public function getNbFautes(): ?int
    {
        return $this->nb_fautes;
    }

    public function setNbFautes(int $nb_fautes): static
    {
        $this->nb_fautes = $nb_fautes;

        return $this;
    }
}
