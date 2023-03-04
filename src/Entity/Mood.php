<?php

namespace App\Entity;

use App\Repository\MoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MoodRepository::class)]
class Mood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?int $MoodId = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?int $UserId = null;
    

    #[ORM\Column]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?string $Mood = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length([
        'min' => 5,
        'minMessage' => 'La description doit contenir au minimum 5 caractères',
    ]),]
    private ?string $Description = null;

    public function __toString()
    {
        return $this->getMood();
    }
   

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoodId(): ?int
    {
        return $this->MoodId;
    }

    public function setMoodId(?int $MoodId): self
    {
        $this->MoodId = $MoodId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->UserId;
    }

    public function setUserId(?int $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getMood(): ?string
    {
        return $this->Mood;
    }

    public function setMood(?string $Mood): self
    {
        $this->Mood = $Mood;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }


}