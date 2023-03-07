<?php

namespace App\Entity;

use App\Repository\EvenementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EvenementsRepository::class)]
#[UniqueEntity(
    fields: ['NomEvenement'],
    message: 'nom déjà utilisé',
)]
class Evenements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("e")]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("e")]
    #[Assert\NotBlank(message:"Nom de l'evenement est obligatoire")]
    #[Assert\Length(min: 5,
    max: 500,
    minMessage: 'Le nom de lévénement doit contenir au moins 3 caractères ',
    maxMessage : 'Le nom de lévénement doit contenir au maximum 500 caractères ',
    
    )]
    

    private ?string $NomEvenement = null;

    #[ORM\Column(length: 255)]
    #[Groups("e")]
    #[Assert\NotBlank(message:"Lieu de l'evenement est obligatoire")]
    

    
    
    private ?string $LieuEvenement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("e")]
    #[Assert\NotBlank(message:"La date est obligatoire")]
    #[Assert\GreaterThanOrEqual("today",message: "La date doit être égale ou postérieure à aujourd'hui")]
    
    
    private ?\DateTimeInterface $DateEvenement = null;

    #[ORM\Column(length: 255)]
    #[Groups("e")]
    #[Assert\NotBlank(message:"Description de l'evenement est obligatoire")]
    #[Assert\Length(min: 5,
    max: 500,
    minMessage: 'La description de lévénement doit contenir au moins 5 caractères ',
    maxMessage : 'La description de lévénement doit contenir au maximum 500 caractères ',
    
    )]
    #[Assert\Type('string' , message:"Description de l'evenement doit etre chaine de caractères")]
    private ?string $DescriptionEvenement = null;

   

    

    #[ORM\Column(nullable: true)]
    #[Groups("e")]
    #[Assert\NotBlank(message:"Le nombre de places est obligatoire")]
    #[Assert\PositiveOrZero(message:'Le nombre de places doit etre supérieur à zéro')]
    private ?int $NbrDePlaces = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("e")]
    private ?string $Type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("e")]
   
    
    private ?string $Image = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups("e")]
    #[Assert\NotBlank(message:"L'heure est ligatoire")]
    private ?\DateTimeInterface $Heure = null;

    #[ORM\OneToMany(mappedBy: 'evenements', targetEntity: Reservation::class ,cascade:['remove'])]
    private Collection $reservations;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups("e")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups("e")]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Likes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Dislikes = null;

    

 
  

    

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

   

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvenement(): ?string
    {
        return $this->NomEvenement;
    }

    public function setNomEvenement(?string $NomEvenement): self
    {
        $this->NomEvenement = $NomEvenement;

        return $this;
    }

    public function getLieuEvenement(): ?string
    {
        return $this->LieuEvenement;
    }

    public function setLieuEvenement(?string $LieuEvenement): self
    {
        $this->LieuEvenement = $LieuEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->DateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $DateEvenement): self
    {
        $this->DateEvenement = $DateEvenement;

        return $this;
    }

    public function getDescriptionEvenement(): ?string
    {
        return $this->DescriptionEvenement;
    }

    public function setDescriptionEvenement(?string $DescriptionEvenement): self
    {
        $this->DescriptionEvenement = $DescriptionEvenement;

        return $this;
    }

   
    

    public function getNbrDePlaces(): ?int
    {
        return $this->NbrDePlaces;
    }

    public function setNbrDePlaces(?int $NbrDePlaces): self
    {
        $this->NbrDePlaces = $NbrDePlaces;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(?string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->Heure;
    }

    public function setHeure(?\DateTimeInterface $Heure): self
    {
        $this->Heure = $Heure;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setEvenements($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEvenements() === $this) {
                $reservation->setEvenements(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLikes(): ?string
    {
        return $this->Likes;
    }

    public function setLikes(?string $Likes): self
    {
        $this->Likes = $Likes;

        return $this;
    }

    public function getDislikes(): ?string
    {
        return $this->Dislikes;
    }

    public function setDislikes(?string $Dislikes): self
    {
        $this->Dislikes = $Dislikes;

        return $this;
    }

}
