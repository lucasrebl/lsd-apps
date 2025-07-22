<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_equipe = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $pays = null;

    #[ORM\Column(length: 100)]
    private ?string $nom_capitaine = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    /**
     * @var Collection<int, Joueur>
     */
    #[ORM\OneToMany(targetEntity: Joueur::class, mappedBy: 'id_equipe')]
    private Collection $joueurs;

    /**
     * @var Collection<int, PouleEquipe>
     */
    #[ORM\OneToMany(targetEntity: PouleEquipe::class, mappedBy: 'id_equipe')]
    private Collection $pouleEquipes;

    /**
     * @var Collection<int, MatchEquipe>
     */
    #[ORM\OneToMany(targetEntity: MatchEquipe::class, mappedBy: 'id_equipe')]
    private Collection $matchEquipes;

    /**
     * @var Collection<int, StatistiqueEquipeTournoi>
     */
    #[ORM\OneToMany(targetEntity: StatistiqueEquipeTournoi::class, mappedBy: 'id_equipe')]
    private Collection $statistiqueEquipeTournois;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        $this->pouleEquipes = new ArrayCollection();
        $this->matchEquipes = new ArrayCollection();
        $this->statistiqueEquipeTournois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_equipe;
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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getNomCapitaine(): ?string
    {
        return $this->nom_capitaine;
    }

    public function setNomCapitaine(string $nom_capitaine): static
    {
        $this->nom_capitaine = $nom_capitaine;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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
     * @return Collection<int, Joueur>
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): static
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
            $joueur->setIdEquipe($this);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): static
    {
        if ($this->joueurs->removeElement($joueur)) {
            // set the owning side to null (unless already changed)
            if ($joueur->getIdEquipe() === $this) {
                $joueur->setIdEquipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PouleEquipe>
     */
    public function getPouleEquipes(): Collection
    {
        return $this->pouleEquipes;
    }

    public function addPouleEquipe(PouleEquipe $pouleEquipe): static
    {
        if (!$this->pouleEquipes->contains($pouleEquipe)) {
            $this->pouleEquipes->add($pouleEquipe);
            $pouleEquipe->setIdEquipe($this);
        }

        return $this;
    }

    public function removePouleEquipe(PouleEquipe $pouleEquipe): static
    {
        if ($this->pouleEquipes->removeElement($pouleEquipe)) {
            // set the owning side to null (unless already changed)
            if ($pouleEquipe->getIdEquipe() === $this) {
                $pouleEquipe->setIdEquipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MatchEquipe>
     */
    public function getMatchEquipes(): Collection
    {
        return $this->matchEquipes;
    }

    public function addMatchEquipe(MatchEquipe $matchEquipe): static
    {
        if (!$this->matchEquipes->contains($matchEquipe)) {
            $this->matchEquipes->add($matchEquipe);
            $matchEquipe->setIdEquipe($this);
        }

        return $this;
    }

    public function removeMatchEquipe(MatchEquipe $matchEquipe): static
    {
        if ($this->matchEquipes->removeElement($matchEquipe)) {
            // set the owning side to null (unless already changed)
            if ($matchEquipe->getIdEquipe() === $this) {
                $matchEquipe->setIdEquipe(null);
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
            $statistiqueEquipeTournoi->setIdEquipe($this);
        }

        return $this;
    }

    public function removeStatistiqueEquipeTournoi(StatistiqueEquipeTournoi $statistiqueEquipeTournoi): static
    {
        if ($this->statistiqueEquipeTournois->removeElement($statistiqueEquipeTournoi)) {
            // set the owning side to null (unless already changed)
            if ($statistiqueEquipeTournoi->getIdEquipe() === $this) {
                $statistiqueEquipeTournoi->setIdEquipe(null);
            }
        }

        return $this;
    }
}
