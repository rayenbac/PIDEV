<?php

namespace App\Entity;

use App\Repository\CabinetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CabinetRepository::class)]
class Cabinet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\OneToMany(mappedBy: 'cabinet', targetEntity: RendezVous::class)]
    private Collection $RV;

    public function __construct()
    {
        $this->RV = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRV(): Collection
    {
        return $this->RV;
    }

    public function addRV(RendezVous $rV): self
    {
        if (!$this->RV->contains($rV)) {
            $this->RV->add($rV);
            $rV->setCabinet($this);
        }

        return $this;
    }

    public function removeRV(RendezVous $rV): self
    {
        if ($this->RV->removeElement($rV)) {
            // set the owning side to null (unless already changed)
            if ($rV->getCabinet() === $this) {
                $rV->setCabinet(null);
            }
        }

        return $this;
    }
}
