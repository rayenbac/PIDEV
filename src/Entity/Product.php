<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'nom deja utilisé',
)]

class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le nom du produit ne doit pas entre vide")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom du produit doit contenir au moin 3 caracteres',
        maxMessage: 'Le nom du produit doit contenir au maximum 100 caracteres',
    )]
    #[Groups("userProducts")]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 1000,
        minMessage: 'La description du produit doit contenir au moin 5 caracteres',
        maxMessage: 'La description produit doit contenir au maximum 1000 caracteres',
    )]
    #[Groups("userProducts")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le prix est obligatoire !")]
    #[Assert\Positive(message: "Le prix doit etre positive")]
    #[Groups("userProducts")]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("userProducts")]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("userProducts")]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: ' La quantite est obligatoire')]
    #[Assert\PositiveOrZero(message: "La quantite doit etre positive")]
    #[Groups("userProducts")]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }



    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

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
}
