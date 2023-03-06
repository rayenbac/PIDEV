<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("info")]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups("info")]
    #[Assert\NotBlank(message:"Le nombre de places est obligatoire")]
    #[Assert\PositiveOrZero(message:'Le nombre de places doit etre supérieur à zéro')]
    private ?int $NombreDePlaceAReserver = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("info")]
    #[Assert\Email(message:"L'email est invalide")]
    #[Assert\NotBlank(message:"L'email est obligatoire")]


    private ?string $Email = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    

    private ?Evenements $evenements = null;

 


    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getNombreDePlaceAReserver(): ?int
    {
        return $this->NombreDePlaceAReserver;
    }

    public function setNombreDePlaceAReserver(?int $NombreDePlaceAReserver): self
    {
        $this->NombreDePlaceAReserver = $NombreDePlaceAReserver;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getEvenement(): ?Evenements
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenements $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getEvenements(): ?Evenements
    {
        return $this->evenements;
    }

    public function setEvenements(?Evenements $evenements): self
    {
        $this->evenements = $evenements;

        return $this;
    }

    

    

   
}
