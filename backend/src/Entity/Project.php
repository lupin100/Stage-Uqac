<?php

namespace App\Entity;

use App\Enum\ProjectEnum;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import important

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['project:read','contributor:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 2500, nullable: true)]
    #[Groups(['project:read','contributor:read'])]
    private ?string $summary = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['project:read','contributor:read'])]
    private ?string $fundingSource = null;

    #[ORM\Column]
    #[Groups(['project:read','contributor:read'])]
    private ?bool $isFinished = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project:read','contributor:read'])]
    private ?string $title = null;

    #[ORM\Column(enumType: ProjectEnum::class)]
    #[Groups(['project:read','contributor:read'])]
    private ?ProjectEnum $thematic = null;

    /**
     * @var Collection<int, Contributor>
     */
    #[ORM\ManyToMany(targetEntity: Contributor::class, mappedBy: 'projects')]
    #[Groups(['project:read'])]
    private Collection $contributors;

 public function __construct()

    {

        $this->contributors = new ArrayCollection();

    }



    public function getId(): ?int

    {

        return $this->id;

    }



    public function getSummary(): ?string

    {

        return $this->summary;

    }



    public function setSummary(?string $summary): static

    {

        $this->summary = $summary;



        return $this;

    }



    public function getFundingSource(): ?string

    {

        return $this->fundingSource;

    }



    public function setFundingSource(?string $fundingSource): static

    {

        $this->fundingSource = $fundingSource;



        return $this;

    }



    public function isFinished(): ?bool

    {

        return $this->isFinished;

    }



    public function setIsFinished(bool $isFinished): static

    {

        $this->isFinished = $isFinished;



        return $this;

    }



    public function getTitle(): ?string

    {

        return $this->title;

    }



    public function setTitle(string $title): static

    {

        $this->title = $title;



        return $this;

    }



    public function getThematic(): ?ProjectEnum

    {

        return $this->thematic;

    }



    public function setThematic(ProjectEnum $thematic): static

    {

        $this->thematic = $thematic;



        return $this;

    }



    /**

     * @return Collection<int, Contributor>

     */

    public function getContributors(): Collection

    {

        return $this->contributors;

    }



    public function addContributor(Contributor $contributor): static

    {

        if (!$this->contributors->contains($contributor)) {

            $this->contributors->add($contributor);

            $contributor->addProject($this);

        }



        return $this;

    }



    public function removeContributor(Contributor $contributor): static

    {

        if ($this->contributors->removeElement($contributor)) {

            $contributor->removeProject($this);

        }



        return $this;

    }

}
