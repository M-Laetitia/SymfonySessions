<?php

namespace App\Entity;

use App\Entity\Session;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]

    #[Assert\NotBlank(message: 'Please select a birthdate')]
    #[Assert\GreaterThanOrEqual(
        "today",
        message: 'Date must be greater than or equal to current date.'
    )]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\ManyToMany(targetEntity: Session::class, inversedBy: 'students')]
    private Collection $sessions;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAge():  string
    {
        $now = new \DateTime();
        $interval = $this->birthday->diff($now);
        return $interval->format("%Y");
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getFormattedPhoneNumber(): ?string
    {
        $phoneNumber = $this->phoneNumber;

        // Vérifier si le numéro de téléphone est vide
        if (empty($phoneNumber)) {
            return null;
        }

        // Supprimer les caractères non numériques
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Formater le numéro de téléphone
        $formattedPhoneNumber = vsprintf('%s.%s.%s.%s.%s', str_split($phoneNumber, 2));

        return $formattedPhoneNumber;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        $this->sessions->removeElement($session);

        return $this;
    }

    public function __toString() {
        
        return $this->lastName. " " .$this->firstName;
    }
}
