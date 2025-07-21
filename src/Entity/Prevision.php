<?php

namespace App\Entity;

use App\Repository\PrevisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrevisionRepository::class)]
class Prevision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_prevision = null;

    /**
     * @var Collection<int, matchs>
     */
    #[ORM\OneToMany(targetEntity: matchs::class, mappedBy: 'prevision')]
    private Collection $id_match;

    #[ORM\Column]
    private ?float $probabilité_victoire_equipe1 = null;

    #[ORM\Column]
    private ?float $probabilité_victoire_equipe2 = null;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    public function __construct()
    {
        $this->id_match = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_prevision;
    }

    /**
     * @return Collection<int, matchs>
     */
    public function getIdMatch(): Collection
    {
        return $this->id_match;
    }

    public function addIdMatch(matchs $idMatch): static
    {
        if (!$this->id_match->contains($idMatch)) {
            $this->id_match->add($idMatch);
            $idMatch->setPrevision($this);
        }

        return $this;
    }

    public function removeIdMatch(matchs $idMatch): static
    {
        if ($this->id_match->removeElement($idMatch)) {
            // set the owning side to null (unless already changed)
            if ($idMatch->getPrevision() === $this) {
                $idMatch->setPrevision(null);
            }
        }

        return $this;
    }

    public function getProbabilitéVictoireEquipe1(): ?float
    {
        return $this->probabilité_victoire_equipe1;
    }

    public function setProbabilitéVictoireEquipe1(float $probabilité_victoire_equipe1): static
    {
        $this->probabilité_victoire_equipe1 = $probabilité_victoire_equipe1;

        return $this;
    }

    public function getProbabilitéVictoireEquipe2(): ?float
    {
        return $this->probabilité_victoire_equipe2;
    }

    public function setProbabilitéVictoireEquipe2(float $probabilité_victoire_equipe2): static
    {
        $this->probabilité_victoire_equipe2 = $probabilité_victoire_equipe2;

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
