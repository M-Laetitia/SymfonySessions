<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: ModuleFormation::class, orphanRemoval: true)]
    private Collection $moduleFormations;

    public function __construct()
    {
        $this->moduleFormation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, moduleFormation>
     */
    public function getModuleFormation(): Collection
    {
        return $this->moduleFormation;
    }

    public function addModuleFormation(ModuleFormation $moduleFormation): static
    {
        if (!$this->moduleFormation->contains($moduleFormation)) {
            $this->moduleFormation->add($moduleFormation);
            $moduleFormation->setCategory($this);
        }

        return $this;
    }

    public function removeModuleFormation(ModuleFormation $moduleFormation): static
    {
        if ($this->moduleFormation->removeElement($moduleFormation)) {
            // set the owning side to null (unless already changed)
            if ($moduleFormation->getCategory() === $this) {
                $moduleFormation->setCategory(null);
            }
        }

        return $this;
    }
}
