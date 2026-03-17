<?php

namespace App\Entity;

use App\Repository\StudentProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentProfileRepository::class)]
class StudentProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $supervisor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coSupervisor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupervisor(): ?string
    {
        return $this->supervisor;
    }

    public function setSupervisor(?string $supervisor): static
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    public function getCoSupervisor(): ?string
    {
        return $this->coSupervisor;
    }

    public function setCoSupervisor(?string $coSupervisor): static
    {
        $this->coSupervisor = $coSupervisor;

        return $this;
    }
}
