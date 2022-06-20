<?php

namespace App\Entity;

use App\Repository\EnseignementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignementsRepository::class)]
class Enseignements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $type;

    #[ORM\ManyToOne(targetEntity: Etablissements::class, inversedBy: 'enseignements')]
    #[ORM\JoinColumn(nullable: false)]
    private $etablissement;

    #[ORM\OneToMany(mappedBy: 'enseignement', targetEntity: Cycles::class, orphanRemoval: true)]
    private $cycles;

    public function __construct()
    {
        $this->cycles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEtablissement(): ?Etablissements
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissements $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * @return Collection<int, Cycles>
     */
    public function getCycles(): Collection
    {
        return $this->cycles;
    }

    public function addCycle(Cycles $cycle): self
    {
        if (!$this->cycles->contains($cycle)) {
            $this->cycles[] = $cycle;
            $cycle->setEnseignement($this);
        }

        return $this;
    }

    public function removeCycle(Cycles $cycle): self
    {
        if ($this->cycles->removeElement($cycle)) {
            // set the owning side to null (unless already changed)
            if ($cycle->getEnseignement() === $this) {
                $cycle->setEnseignement(null);
            }
        }

        return $this;
    }
}
