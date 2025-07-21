<?php

namespace App\Entity;

use App\Repository\PouleEquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PouleEquipeRepository::class)]
class PouleEquipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_poule_equipe = null;

    /**
     * @var Collection<int, equipe>
     */
    #[ORM\ManyToMany(targetEntity: equipe::class, inversedBy: 'pouleEquipes')]
    private Collection $id_equipe;

    /**
     * @var Collection<int, poule>
     */
    #[ORM\ManyToMany(targetEntity: poule::class, inversedBy: 'pouleEquipes')]
    private Collection $id_poule;

    #[ORM\Column]
    private ?\DateTime $date_creation = null;

    public function __construct()
    {
        $this->id_equipe = new ArrayCollection();
        $this->id_poule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_poule_equipe;
    }

    /**
     * @return Collection<int, equipe>
     */
    public function getIdEquipe(): Collection
    {
        return $this->id_equipe;
    }

    public function addIdEquipe(equipe $idEquipe): static
    {
        if (!$this->id_equipe->contains($idEquipe)) {
            $this->id_equipe->add($idEquipe);
        }

        return $this;
    }

    public function removeIdEquipe(equipe $idEquipe): static
    {
        $this->id_equipe->removeElement($idEquipe);

        return $this;
    }

    /**
     * @return Collection<int, poule>
     */
    public function getIdPoule(): Collection
    {
        return $this->id_poule;
    }

    public function addIdPoule(poule $idPoule): static
    {
        if (!$this->id_poule->contains($idPoule)) {
            $this->id_poule->add($idPoule);
        }

        return $this;
    }

    public function removeIdPoule(poule $idPoule): static
    {
        $this->id_poule->removeElement($idPoule);

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
