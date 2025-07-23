<?php

namespace App\Entity;

use App\Repository\MatchsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchsRepository::class)]
class Matchs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_match = null;

    #[ORM\Column]
    private ?\DateTime $date_heure = null;

    #[ORM\Column(length: 100)]
    private ?string $lieu = null;

    #[ORM\Column(length: 50)]
    private ?string $phase = null;

    #[ORM\ManyToOne(inversedBy: 'matchs')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_poule')]
    private ?Poule $id_poule = null;

    #[ORM\ManyToOne(inversedBy: 'matchs')]
    #[ORM\JoinColumn(nullable: true, referencedColumnName: 'id_tableau')]
    private ?Tableau $id_tableau = null;

    #[ORM\ManyToOne(inversedBy: 'matchs')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id_tournoi')]
    private ?Tournoi $id_tournoi = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    /**
     * @var Collection<int, MatchEquipe>
     */
    #[ORM\OneToMany(targetEntity: MatchEquipe::class, mappedBy: 'id_match')]
    private Collection $matchEquipes;

    #[ORM\OneToOne(mappedBy: 'match', cascade: ['persist', 'remove'])]
    private ?Resultat $resultat = null;

    #[ORM\OneToOne(mappedBy: 'match', cascade: ['persist', 'remove'])]
    private ?Prevision $prevision = null;

    public function __construct()
    {
        $this->matchEquipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_match;
    }

    public function getDateHeure(): ?\DateTime
    {
        return $this->date_heure;
    }

    public function setDateHeure(\DateTime $date_heure): static
    {
        $this->date_heure = $date_heure;

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

    public function getPhase(): ?string
    {
        return $this->phase;
    }

    public function setPhase(string $phase): static
    {
        $this->phase = $phase;

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

    public function getIdTableau(): ?Tableau
    {
        return $this->id_tableau;
    }

    public function setIdTableau(?Tableau $id_tableau): static
    {
        $this->id_tableau = $id_tableau;

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
            $matchEquipe->setIdMatch($this);
        }

        return $this;
    }

    public function removeMatchEquipe(MatchEquipe $matchEquipe): static
    {
        if ($this->matchEquipes->removeElement($matchEquipe)) {
            // set the owning side to null (unless already changed)
            if ($matchEquipe->getIdMatch() === $this) {
                $matchEquipe->setIdMatch(null);
            }
        }

        return $this;
    }

    public function getResultat(): ?Resultat
    {
        return $this->resultat;
    }

    public function setResultat(?Resultat $resultat): static
    {
        $this->resultat = $resultat;

        return $this;
    }

    public function getPrevision(): ?Prevision
    {
        return $this->prevision;
    }

    public function setPrevision(?Prevision $prevision): static
    {
        $this->prevision = $prevision;

        return $this;
    }
}
