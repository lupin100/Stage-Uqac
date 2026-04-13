<?php

namespace App\Entity;

use App\Repository\InstitutionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; 
#[ORM\Entity(repositoryClass: InstitutionRepository::class)]
class Institution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['person:read', 'institution:read', 'departement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['person:read', 'institution:read', 'departement:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['person:read', 'institution:read', 'departement:read'])]
    private ?string $url = null;

    #[ORM\OneToOne(mappedBy: 'institution', cascade: ['persist', 'remove'])]
    #[Groups(['institution:read'])]
    private ?Person $person = null;

    #[ORM\ManyToOne(inversedBy: 'institution')]
    #[Groups(['person:read', 'institution:read'])]
    private ?Departement $departement = null;

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
        if ($person === null && $this->person !== null) {
            $this->person->setInstitution(null);
        }

        if ($person !== null && $person->getInstitution() !== $this) {
            $person->setInstitution($this);
        }

        $this->person = $person;
        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;
        return $this;
    }
}
