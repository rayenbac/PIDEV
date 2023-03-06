<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Text = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'Notif')]
    private Collection $Notif;

    #[ORM\Column]
    private ?string $EmailUser = null;

    #[ORM\Column]
    private ?bool $Status = null;

    #[ORM\Column(length: 255)]
    private ?string $Code = null;

    public function __construct()
    {
        $this->Notif = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(?string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getNotif(): Collection
    {
        return $this->Notif;
    }

    public function addNotif(User $notif): self
    {
        if (!$this->Notif->contains($notif)) {
            $this->Notif->add($notif);
            $notif->addNotif($this);
        }

        return $this;
    }

    public function removeNotif(User $notif): self
    {
        if ($this->Notif->removeElement($notif)) {
            $notif->removeNotif($this);
        }

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->EmailUser;
    }

    public function setEmailUser(string $EmailUser): self
    {
        $this->EmailUser = $EmailUser;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

        return $this;
    }
}
