<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\OneToOne(mappedBy: 'departement', cascade: ['persist', 'remove'])]
    private ?Person $person = null;

    /**
     * @var Collection<int, Institution>
     */
    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: 'departement')]
    private Collection $institution;

    public function __construct()
    {
        $this->institution = new ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        // unset the owning side of the relation if necessary
        if ($person === null && $this->person !== null) {
            $this->person->setDepartement(null);
        }

        // set the owning side of the relation if necessary
        if ($person !== null && $person->getDepartement() !== $this) {
            $person->setDepartement($this);
        }

        $this->person = $person;

        return $this;
    }

    /**
     * @return Collection<int, Institution>
     */
    public function getInstitution(): Collection
    {
        return $this->institution;
    }

    public function addInstitution(Institution $institution): static
    {
        if (!$this->institution->contains($institution)) {
            $this->institution->add($institution);
            $institution->setDepartement($this);
        }

        return $this;
    }

    public function removeInstitution(Institution $institution): static
    {
        if ($this->institution->removeElement($institution)) {
            // set the owning side to null (unless already changed)
            if ($institution->getDepartement() === $this) {
                $institution->setDepartement(null);
            }
        }

        return $this;
    }


}
