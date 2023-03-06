<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("post")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("post")]
    #[Assert\Positive (message:"Vérifier votre ID")]
    #[Assert\NotBlank (message:"ID est obligatoire")]
    private ?int $ID_user = null;

    #[ORM\Column(length: 255)]
    #[Groups("post")]
    #[Assert\NotBlank (message:"Description est obligatoire")]
    #[Assert\Length([
        'max' => 30,
        'maxMessage' => 'La description ne doit pas dépasser 30 caractères',
    ]),]
    #[Assert\Regex([
        'pattern' => '/^\D+$/',
        'message' => 'Le champ ne doit pas contenir des chiffres',
    ]),]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    #[Groups("post")]
    #[Assert\NotBlank (message:"Question est obligatoire")]
    private ?string $Publication = null;
   
    #[ORM\OneToMany(mappedBy: 'commentaires', targetEntity: Commentaire::class , cascade: ['remove'])]
    private Collection $commentaires;

    #[ORM\Column(length: 255)]
    #[Groups("post")]
    #[Assert\NotBlank (message:"Nom utilisateur est obligatoire")]
    #[Assert\Length([
        'max' => 20,
        'maxMessage' => 'La description ne doit pas dépasser 20 caractères',
    ]),]
    
    private ?string $NomUtilisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("post")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("post")]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $likes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Dislike = null;

    #[ORM\Column(nullable: true)]
    private ?int $rate = null;

   

  

   

   
   

  
    
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIDUser(): ?int
    {
        return $this->ID_user;
    }

    public function setIDUser(?int $ID_user): self
    {
        $this->ID_user = $ID_user;

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

   

    public function getPublication(): ?string
    {
        return $this->Publication;
    }

    public function setPublication(?string $Publication): self
    {
        $this->Publication = $Publication;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setCommentaires($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCommentaires() === $this) {
                $commentaire->setCommentaires(null);
            }
        }

        return $this;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->NomUtilisateur;
    }

    public function setNomUtilisateur(?string $NomUtilisateur): self
    {
        $this->NomUtilisateur = $NomUtilisateur;

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
        return $this->likes;
    }

    public function setLikes(?string $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislike(): ?string
    {
        return $this->Dislike;
    }

    public function setDislike(?string $Dislike): self
    {
        $this->Dislike = $Dislike;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }


   

   

   

   

    

    

   
   

   

  

    
  
    

   

    
    
}
