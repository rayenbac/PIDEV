<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("commentaire")]

    private ?int $id = null;

    #[ORM\Column]
    #[Groups("commentaire")]
    private ?int $Id_user = null;

    #[ORM\Column(length: 255)]
    #[Groups("commentaire")]
    private ?string $Reponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("commentaire")]
    private ?\DateTimeInterface $Date = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires',)]

    private ?Post $commentaires = null;

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

    public function getReponse(): ?string
    {
        return $this->Reponse;
    }

    public function setReponse(string $Reponse): self
    {
        $this->Reponse = $Reponse;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCommentaires(): ?Post
    {
        return $this->commentaires;
    }

    public function setCommentaires(?Post $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }
    public function __toString(): String
    {
        return $this->getReponse();
    }
}
