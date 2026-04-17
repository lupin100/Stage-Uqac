<?php

namespace App\Entity;

use App\Repository\ContributorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import indispensable

#[ORM\Entity(repositoryClass: ContributorRepository::class)]
class Contributor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contributor:read', 'publication:read','project:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['contributor:read', 'publication:read','project:read'])]
    private ?string $displayName = null;



    #[ORM\OneToOne(inversedBy: 'contributor', cascade: ['persist', 'remove'])]
    #[Groups(['publication:read', 'contributor:read','project:read'])]
    private ?Person $person = null;

    /**
     * @var Collection<int, Publication>
     */
    #[ORM\ManyToMany(targetEntity: Publication::class, inversedBy: 'contributors')]
    #[Groups(['contributor:read'])]
    private Collection $publications;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'contributors')]
    #[Groups(['contributor:read'])]
    private Collection $projects;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;
        return $this;
    }



    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): static
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
        }

        return $this;
    }

    public function removePublication(Publication $publication): static
    {
        $this->publications->removeElement($publication);

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        $this->projects->removeElement($project);

        return $this;
    }

    public function __toString(): string
    {
        return $this->displayName ?? 'N/A';
    }
}
