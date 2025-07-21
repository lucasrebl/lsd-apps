<?php

namespace App\Entity;

use App\Repository\PouleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PouleRepository::class)]
class Poule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_poule = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'poules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?tournoi $id_tournoi = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    /**
     * @var Collection<int, PouleEquipe>
     */
    #[ORM\ManyToMany(targetEntity: PouleEquipe::class, mappedBy: 'id_poule')]
    private Collection $pouleEquipes;

    /**
     * @var Collection<int, Matchs>
     */
    #[ORM\OneToMany(targetEntity: Matchs::class, mappedBy: 'id_poule')]
    private Collection $matchs;

    public function __construct()
    {
        $this->pouleEquipes = new ArrayCollection();
        $this->matchs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_poule;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getIdTournoi(): ?tournoi
    {
        return $this->id_tournoi;
    }

    public function setIdTournoi(?tournoi $id_tournoi): static
    {
        $this->id_tournoi = $id_tournoi;

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
            $pouleEquipe->addIdPoule($this);
        }

        return $this;
    }

    public function removePouleEquipe(PouleEquipe $pouleEquipe): static
    {
        if ($this->pouleEquipes->removeElement($pouleEquipe)) {
            $pouleEquipe->removeIdPoule($this);
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
            $match->setIdPoule($this);
        }

        return $this;
    }

    public function removeMatch(Matchs $match): static
    {
        if ($this->matchs->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getIdPoule() === $this) {
                $match->setIdPoule(null);
            }
        }

        return $this;
    }
}
