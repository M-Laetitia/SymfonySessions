<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Session;
use App\Entity\ModuleFormation;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Please enter an email')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]

    private ?string $password = null;

    // #[ORM\Column(length: 255)]
    // #[Assert\EqualTo(
    //     propertyPath: 'repeatPassword',
    //     message: 'The 2 passwords must match'
    // )]
    // private ?string $repeatPassword = null;



    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Please enter a first name')]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Please enter a last name')]
    private ?string $lastName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $avatar = null;


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }


    #[ORM\Column]
    private ?bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Session::class, orphanRemoval: true)]
    private Collection $sessions;

    #[ORM\ManyToMany(targetEntity: ModuleFormation::class, inversedBy: 'users')]
    private Collection $moduleFormations;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->moduleFormations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    // public function getAvatar(): ?string
    // {
    //     return $this->avatar;
    // }

    // public function setAvatar(?string $avatar): static
    // {
    //     $this->avatar = $avatar;

    //     return $this;
    // }



    // public function setAvatar(?File $avatar): static
    // {
    //     $this->avatar = $avatar;

    //     if ($avatar) {
    //         // Mettez à jour la propriété "updatedAt" pour déclencher le téléchargement
    //         $this->updatedAt = new DateTimeImmutable();
    //     }

    //     return $this;
    // }



    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $session->setUser($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getUser() === $this) {
                $session->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, moduleFormation>
     */
    public function getModuleFormations(): Collection
    {
        return $this->moduleFormations;
    }

    public function addModuleFormation(ModuleFormation $moduleFormation): static
    {
        if (!$this->moduleFormations->contains($moduleFormation)) {
            $this->moduleFormations->add($moduleFormation);
        }

        return $this;
    }

    public function removeModuleFormation(ModuleFormation $moduleFormation): static
    {
        $this->moduleFormations->removeElement($moduleFormation);

        return $this;
    }





    public function __toString() {
        return $this->firstName. " " .$this->lastName;
    }

    // public function isVerified(): bool
    // {
    //     return $this->isVerified;
    // }
}
