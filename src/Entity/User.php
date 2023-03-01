<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("users")]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email ne doit pas etre vide")]
    #[Groups("users")]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups("users")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mote de passe est obligatoire !")]
    #[Groups("users")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prenom est obligatoire !")]
    #[Groups("users")]
    private ?string $FirstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire !")]
    #[Groups("users")]
    private ?string $LastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le prenom est obligatoire !")]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "L'adresse  doit contenir au moins 3 caracteres",
        maxMessage: "L'adresse  doit contenir au maximum 100 caracteres",
    )]
    #[Groups("users")]
    private ?string $Adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "La date doit etre rempli !")]
    #[Groups("users")]
    private ?\DateTimeInterface $BirthDate = null;

        
    #[Assert\NotBlank(message: "Le téléphone est obligatoire !")]
    #[Assert\Positive(message: "Le téléphone doit etre un nombre positive !")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "Le numéro de téléphone  doit contenir au moins 8 caracteres",
        
    )]
    #[Groups("users")]
    private ?string $PhoneNumber = null;

    #[ORM\Column(nullable: true)]
    #[Groups("users")]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Groups("users")]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    #[Groups("users")]
    private ?bool $Status = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("users")]
    private ?string $file = null;

    #[ORM\Column(length: 255)]
    #[Groups("users")]
    private ?string $Gender = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(?string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->BirthDate;
    }

    public function setBirthDate(\DateTimeInterface $BirthDate): self
    {
        $this->BirthDate = $BirthDate;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber(string $PhoneNumber): self
    {
        $this->PhoneNumber = $PhoneNumber;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(?bool $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): self
    {
        $this->Gender = $Gender;

        return $this;
    }


   
}
