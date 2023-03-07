<?php

namespace App\Entity;




use App\Repository\JournalMoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\JournalMood;
use App\Entity\Mood;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JournalMoodRepository::class)]
class JournalMood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("journals")]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Groups("journals")]
    private ?int $IdUser = null;

    #[ORM\ManyToOne]
    #[Groups("journals")]
    private ?Mood $moods = null;

    public function __toString()
    {
         $this->moods;
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

    public function getMoods(): ?Mood
    {
        return $this->moods;
    }

    public function setMoods(?Mood $moods): self
    {
        $this->moods = $moods;

        return $this;
    }

}
