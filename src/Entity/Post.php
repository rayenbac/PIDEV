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


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Positive (message:"Vérifier votre ID")]
    #[Assert\NotBlank (message:"ID es required")]
    private ?int $ID_user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Description es required")]
    #[Assert\Length([
        'max' => 20,
        'maxMessage' => 'La description ne doit pas dépasser 20 caractères',
    ]),]
    #[Assert\Regex([
        'pattern' => '/^\D+$/',
        'message' => 'Le champ ne doit pas contenir des chiffres',
    ]),]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Question es required")]
    private ?string $Publication = null;
   
    #[ORM\OneToMany(mappedBy: 'commentaires', targetEntity: Commentaire::class , cascade: ['remove'])]
    private Collection $commentaires;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"User name es required")]
    #[Assert\Length([
        'max' => 20,
        'maxMessage' => 'La description ne doit pas dépasser 10 caractères',
    ]),]
    
    private ?string $NomUtilisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

   

  

   

   
   

  
    
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

    public function setIDUser(int $ID_user): self
    {
        $this->ID_user = $ID_user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

   

    public function getPublication(): ?string
    {
        return $this->Publication;
    }

    public function setPublication(string $Publication): self
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

    public function setNomUtilisateur(string $NomUtilisateur): self
    {
        $this->NomUtilisateur = $NomUtilisateur;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

   

   

   

   

    

    

   
   

   

  

    
  
    

   

    
    
}
