<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("article")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("article")]
    #[Assert\Positive(message: "Vérifier votre ID")]
    #[Assert\NotBlank(message: "ID est obligatoire")]
    private ?int $Id_user = null;

    #[ORM\Column(length: 255)]
    #[Groups("article")]
    #[Assert\NotBlank(message: "Article est obligatoire")]
    private ?string $article = null;



    #[ORM\Column(length: 255)]
    #[Groups("article")]
    #[Assert\NotBlank(message: "Name est obligatoire")]
    #[Assert\Length([
        'max' => 20,
        'maxMessage' => 'La description ne doit pas dépasser 20 caractères',
    ]),]

    private ?string $NomUtilisateur = null;

    #[ORM\Column(length: 255)]
    #[Groups("article")]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("article")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("article")]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->Id_user;
    }

    public function setIdUser(int $Id_user): self
    {
        $this->Id_user = $Id_user;

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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
