<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentRepository::class)]
class Rent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $rentedFrom;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $rentedUntil;

    #[ORM\Column]
    private bool $approved;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRentedFrom(): \DateTimeInterface
    {
        return $this->rentedFrom;
    }

    public function setRentedFrom(\DateTimeInterface $rentedFrom): static
    {
        $this->rentedFrom = $rentedFrom;

        return $this;
    }

    public function getRentedUntil(): \DateTimeInterface
    {
        return $this->rentedUntil;
    }

    public function setRentedUntil(\DateTimeInterface $rentedUntil): static
    {
        $this->rentedUntil = $rentedUntil;

        return $this;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
