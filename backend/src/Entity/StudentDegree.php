<?php

namespace App\Entity;

use App\Enum\StudentDegreeEnum;
use App\Repository\StudentDegreeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import indispensable

#[ORM\Entity(repositoryClass: StudentDegreeRepository::class)]
class StudentDegree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['person:read', 'student:read', 'degree:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['person:read', 'student:read', 'degree:read'])]
    private ?int $startYear = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['person:read', 'student:read', 'degree:read'])]
    private ?int $endYear = null;

    #[ORM\Column(enumType: StudentDegreeEnum::class)]
    #[Groups(['person:read', 'student:read', 'degree:read'])]
    private ?StudentDegreeEnum $degree = null;

    /**
     * @var Collection<int, StudentProfile>
     */
    #[ORM\OneToMany(targetEntity: StudentProfile::class, mappedBy: 'studentDegree')]
    #[Groups(['degree:read'])]
    // On ne met PAS 'person:read' ou 'student:read' ici pour ne pas renvoyer
    // tous les profils étudiants rattachés à ce diplôme quand on regarde une seule personne.
    private Collection $studentProfile;

    public function __construct()
    {
        $this->studentProfile = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): static
    {
        $this->startYear = $startYear;
        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): static
    {
        $this->endYear = $endYear;
        return $this;
    }

    public function getDegree(): ?StudentDegreeEnum
    {
        return $this->degree;
    }

    public function setDegree(StudentDegreeEnum $degree): static
    {
        $this->degree = $degree;
        return $this;
    }

    /**
     * @return Collection<int, StudentProfile>
     */
    public function getStudentProfile(): Collection
    {
        return $this->studentProfile;
    }

    public function addStudentProfile(StudentProfile $studentProfile): static
    {
        if (!$this->studentProfile->contains($studentProfile)) {
            $this->studentProfile->add($studentProfile);
            $studentProfile->setStudentDegree($this);
        }

        return $this;
    }

    public function removeStudentProfile(StudentProfile $studentProfile): static
    {
        if ($this->studentProfile->removeElement($studentProfile)) {
            if ($studentProfile->getStudentDegree() === $this) {
                $studentProfile->setStudentDegree(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->degree ? $this->degree->name : 'N/A' . ' (' . ($this->startYear ?? 'N/A') . ' - ' . ($this->endYear ?? 'N/A') . ')';
    }
}
