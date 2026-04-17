<?php

namespace App\Entity;

use App\Enum\PublicationEnum;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import crucial

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contributor:read', 'publication:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['person:read', 'contributor:read', 'publication:read'])]
    private ?string $title = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['contributor:read', 'publication:read'])]
    private ?int $year = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['contributor:read', 'publication:read'])]
    private ?string $externalUrl = null;

    #[ORM\Column(enumType: PublicationEnum::class)]
    #[Groups(['contributor:read', 'publication:read'])]
    private ?PublicationEnum $publicationType = null;

    /**
     * @var Collection<int, Contributor>
     */
    #[ORM\ManyToMany(targetEntity: Contributor::class, mappedBy: 'publications')]
    #[Groups(['publication:read'])]
    private Collection $contributors;

    public function __construct()
    {
        $this->contributors = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function getExternalUrl(): ?string
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?string $externalUrl): static
    {
        $this->externalUrl = $externalUrl;
        return $this;
    }

    public function getPublicationType(): ?PublicationEnum
    {
        return $this->publicationType;
    }

    public function setPublicationType(PublicationEnum $publicationType): static
    {
        $this->publicationType = $publicationType;
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
            $contributor->addPublication($this);
        }

        return $this;
    }

    public function removeContributor(Contributor $contributor): static
    {
        if ($this->contributors->removeElement($contributor)) {
            $contributor->removePublication($this);
        }

        return $this;
    }


}
