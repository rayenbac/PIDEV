<?php

namespace App\Entity;




use App\Repository\JournalMoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\JournalMood;
use App\Entity\Mood;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JournalMoodRepository::class)]
class JournalMood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?int $IdUser = null;

    #[ORM\OneToMany(mappedBy: 'journalMood', targetEntity: Mood::class)]
    private Collection $moods;

    public function __construct()
    {
        $this->moods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->IdUser;
    }

    public function setIdUser(?int $IdUser): self
    {
        $this->IdUser = $IdUser;

        return $this;
    }

    /**
     * @return Collection<int, Mood>
     */
    public function getMoods(): Collection
    {
        return $this->moods;
    }

    public function addMood(Mood $mood): self
    {
        if (!$this->moods->contains($mood)) {
            $this->moods->add($mood);
            $mood->setJournalMood($this);
        }

        return $this;
    }

    public function removeMood(Mood $mood): self
    {
        if ($this->moods->removeElement($mood)) {
            // set the owning side to null (unless already changed)
            if ($mood->getJournalMood() === $this) {
                $mood->setJournalMood(null);
            }
        }

        return $this;
    }
}
