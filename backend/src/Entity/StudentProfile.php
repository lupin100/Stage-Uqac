<?php

namespace App\Entity;

use App\Repository\StudentProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import indispensable

#[ORM\Entity(repositoryClass: StudentProfileRepository::class)]
class StudentProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['person:read', 'student:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['person:read', 'student:read'])]
    private ?string $supervisor = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['person:read', 'student:read'])]
    private ?string $coSupervisor = null;

    #[ORM\OneToOne(mappedBy: 'studentProfile', cascade: ['persist', 'remove'])]
    #[Groups(['student:read'])]
    // On ne met PAS 'person:read' ici pour briser la boucle
    private ?Person $person = null;

    #[ORM\ManyToOne(inversedBy: 'studentProfile')]
    #[Groups(['person:read', 'student:read'])]
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
        if ($person === null && $this->person !== null) {
            $this->person->setStudentProfile(null);
        }

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
