<?php

namespace App\Entity;

use App\Repository\TableauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableauRepository::class)]
class Tableau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_tableau = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom_phase = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $odre = null;

    #[ORM\ManyToOne(inversedBy: 'tableaus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?tournoi $id_tournoi = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    /**
     * @var Collection<int, Matchs>
     */
    #[ORM\OneToMany(targetEntity: Matchs::class, mappedBy: 'id_tableau')]
    private Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_tableau;
    }

    public function getNomPhase(): ?string
    {
        return $this->nom_phase;
    }

    public function setNomPhase(?string $nom_phase): static
    {
        $this->nom_phase = $nom_phase;

        return $this;
    }

    public function getOdre(): ?string
    {
        return $this->odre;
    }

    public function setOdre(?string $odre): static
    {
        $this->odre = $odre;

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
            $match->setIdTableau($this);
        }

        return $this;
    }

    public function removeMatch(Matchs $match): static
    {
        if ($this->matchs->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getIdTableau() === $this) {
                $match->setIdTableau(null);
            }
        }

        return $this;
    }
}
