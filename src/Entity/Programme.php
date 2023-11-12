<?php
 
namespace App\Entity;
 
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;
 
#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
 
    #[ORM\Column]
    private ?int $duration = null;
 
    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Session $session = null;
 
    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModuleFormation $moduleFormation = null;
 
    public function getId(): ?int
    {
        return $this->id;
    }
 
    public function getDuration(): ?int
    {
        return $this->duration;
    }
 
    public function setDuration(int $duration): static
    {
        $this->duration = $duration;
 
        return $this;
    }
 
    public function getSession(): ?Session
    {
        return $this->session;
    }
 
    public function setSession(?Session $session): static
    {
        $this->session = $session;
 
        return $this;
    }
 
    public function getModuleFormation(): ?ModuleFormation
    {
        return $this->moduleFormation;
    }
 
    public function setModuleFormation(?ModuleFormation $moduleFormation): self
    {
        $this->moduleFormation = $moduleFormation;
 
        return $this;
    }
}