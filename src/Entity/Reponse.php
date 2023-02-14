<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Reponse = null;
    #[Assert\NotBlank (message:"Reponse es required")]

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Question $Reponses = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->Reponse;
    }

    public function setReponse(string $Reponse): self
    {
        $this->Reponse = $Reponse;

        return $this;
    }

    public function getReponses(): ?Question
    {
        return $this->Reponses;
    }

    public function setReponses(?Question $Reponses): self
    {
        $this->Reponses = $Reponses;

        return $this;
    }

}
