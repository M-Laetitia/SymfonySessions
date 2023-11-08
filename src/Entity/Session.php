<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?int $nbPlaceTotal = null;

    #[ORM\Column]
    private ?int $nbPlaceBooked = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getNbPlaceTotal(): ?int
    {
        return $this->nbPlaceTotal;
    }

    public function setNbPlaceTotal(int $nbPlaceTotal): static
    {
        $this->nbPlaceTotal = $nbPlaceTotal;

        return $this;
    }

    public function getNbPlaceBooked(): ?int
    {
        return $this->nbPlaceBooked;
    }

    public function setNbPlaceBooked(int $nbPlaceBooked): static
    {
        $this->nbPlaceBooked = $nbPlaceBooked;

        return $this;
    }
}
