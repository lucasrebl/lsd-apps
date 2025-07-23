<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]
class Tournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_tournoi = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_fin = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    /**
     * @var Collection<int, Poule>
     */
    #[ORM\OneToMany(targetEntity: Poule::class, mappedBy: 'id_tournoi')]
    private Collection $poules;

    /**
     * @var Collection<int, Tableau>
     */
    #[ORM\OneToMany(targetEntity: Tableau::class, mappedBy: 'id_tournoi')]
    private Collection $tableaus;

    /**
     * @var Collection<int, Matchs>
     */
    #[ORM\OneToMany(targetEntity: Matchs::class, mappedBy: 'id_tournoi')]
    private Collection $matchs;

    /**
     * @var Collection<int, StatistiqueEquipeTournoi>
     */
    #[ORM\OneToMany(targetEntity: StatistiqueEquipeTournoi::class, mappedBy: 'id_tournoi')]
    private Collection $statistiqueEquipeTournois;

    /**
     * @var Collection<int, Equipe>
     */
    #[ORM\ManyToMany(targetEntity: Equipe::class, mappedBy: 'tournoisInscrits')]
    private Collection $equipesInscrites;

    public function __construct()
    {
        $this->poules = new ArrayCollection();
        $this->tableaus = new ArrayCollection();
        $this->matchs = new ArrayCollection();
        $this->statistiqueEquipeTournois = new ArrayCollection();
        $this->equipesInscrites = new ArrayCollection();
        $this->date_creation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id_tournoi;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTime $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTime $date_fin): static
    {
        $this->date_fin = $date_fin;

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

    /**
     * @return Collection<int, Poule>
     */
    public function getPoules(): Collection
    {
        return $this->poules;
    }

    public function addPoule(Poule $poule): static
    {
        if (!$this->poules->contains($poule)) {
            $this->poules->add($poule);
            $poule->setIdTournoi($this);
        }

        return $this;
    }

    public function removePoule(Poule $poule): static
    {
        if ($this->poules->removeElement($poule)) {
            // set the owning side to null (unless already changed)
            if ($poule->getIdTournoi() === $this) {
                $poule->setIdTournoi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tableau>
     */
    public function getTableaus(): Collection
    {
        return $this->tableaus;
    }

    public function addTableau(Tableau $tableau): static
    {
        if (!$this->tableaus->contains($tableau)) {
            $this->tableaus->add($tableau);
            $tableau->setIdTournoi($this);
        }

        return $this;
    }

    public function removeTableau(Tableau $tableau): static
    {
        if ($this->tableaus->removeElement($tableau)) {
            // set the owning side to null (unless already changed)
            if ($tableau->getIdTournoi() === $this) {
                $tableau->setIdTournoi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matchs>
     */
    public function getMatchs(): Collection
    {
        return $this->matchs;
    }

    public function addMatch(Matchs $match): static
    {
        if (!$this->matchs->contains($match)) {
            $this->matchs->add($match);
            $match->setIdTournoi($this);
        }

        return $this;
    }

    public function removeMatch(Matchs $match): static
    {
        if ($this->matchs->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getIdTournoi() === $this) {
                $match->setIdTournoi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StatistiqueEquipeTournoi>
     */
    public function getStatistiqueEquipeTournois(): Collection
    {
        return $this->statistiqueEquipeTournois;
    }

    public function addStatistiqueEquipeTournoi(StatistiqueEquipeTournoi $statistiqueEquipeTournoi): static
    {
        if (!$this->statistiqueEquipeTournois->contains($statistiqueEquipeTournoi)) {
            $this->statistiqueEquipeTournois->add($statistiqueEquipeTournoi);
            $statistiqueEquipeTournoi->setIdTournoi($this);
        }

        return $this;
    }

    public function removeStatistiqueEquipeTournoi(StatistiqueEquipeTournoi $statistiqueEquipeTournoi): static
    {
        if ($this->statistiqueEquipeTournois->removeElement($statistiqueEquipeTournoi)) {
            // set the owning side to null (unless already changed)
            if ($statistiqueEquipeTournoi->getIdTournoi() === $this) {
                $statistiqueEquipeTournoi->setIdTournoi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipesInscrites(): Collection
    {
        return $this->equipesInscrites;
    }
}
