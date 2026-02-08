<?php

namespace App\Entity;

use App\Repository\ContributorRepository;
use BcMath\Number;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContributorRepository::class)]
class Contributor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $contributorOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContributorOrder(): ?int
    {
        return $this->contributorOrder;
    }

    public function setContributorOrder(int $contributorOrder): static
    {
        $this->contributorOrder = $contributorOrder;

        return $this;
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
}
