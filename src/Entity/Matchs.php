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
    #[ORM\JoinColumn(nullable: false)]
    private ?poule $id_poule = null;

    #[ORM\ManyToOne(inversedBy: 'matchs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?tableau $id_tableau = null;

    #[ORM\ManyToOne(inversedBy: 'matchs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?tournoi $id_tournoi = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    /**
     * @var Collection<int, MacthEquipe>
     */
    #[ORM\OneToMany(targetEntity: MacthEquipe::class, mappedBy: 'id_match')]
    private Collection $macthEquipes;

    #[ORM\ManyToOne(inversedBy: 'id_match')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resultat $resultat = null;

    #[ORM\ManyToOne(inversedBy: 'id_match')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prevision $prevision = null;

    public function __construct()
    {
        $this->macthEquipes = new ArrayCollection();
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

    public function getIdPoule(): ?poule
    {
        return $this->id_poule;
    }

    public function setIdPoule(?poule $id_poule): static
    {
        $this->id_poule = $id_poule;

        return $this;
    }

    public function getIdTableau(): ?tableau
    {
        return $this->id_tableau;
    }

    public function setIdTableau(?tableau $id_tableau): static
    {
        $this->id_tableau = $id_tableau;

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
     * @return Collection<int, MacthEquipe>
     */
    public function getMacthEquipes(): Collection
    {
        return $this->macthEquipes;
    }

    public function addMacthEquipe(MacthEquipe $macthEquipe): static
    {
        if (!$this->macthEquipes->contains($macthEquipe)) {
            $this->macthEquipes->add($macthEquipe);
            $macthEquipe->setIdMatch($this);
        }

        return $this;
    }

    public function removeMacthEquipe(MacthEquipe $macthEquipe): static
    {
        if ($this->macthEquipes->removeElement($macthEquipe)) {
            // set the owning side to null (unless already changed)
            if ($macthEquipe->getIdMatch() === $this) {
                $macthEquipe->setIdMatch(null);
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
