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

    #[ORM\OneToOne(mappedBy: 'studentProfile', cascade: ['persist', 'remove'])]
    private ?Person $person = null;

    #[ORM\ManyToOne(inversedBy: 'studentProfile')]
    private ?StudentDegree $studentDegree = null;

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

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        // unset the owning side of the relation if necessary
        if ($person === null && $this->person !== null) {
            $this->person->setStudentProfile(null);
        }

        // set the owning side of the relation if necessary
        if ($person !== null && $person->getStudentProfile() !== $this) {
            $person->setStudentProfile($this);
        }

        $this->person = $person;

        return $this;
    }

    public function getStudentDegree(): ?StudentDegree
    {
        return $this->studentDegree;
    }

    public function setStudentDegree(?StudentDegree $studentDegree): static
    {
        $this->studentDegree = $studentDegree;

        return $this;
    }
}
