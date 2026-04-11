<?php

namespace App\Entity;

use App\Enum\StudentDegreeEnum;
use App\Repository\StudentDegreeRepository;
use BcMath\Number;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentDegreeRepository::class)]
class StudentDegree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $startYear = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $endYear = null;

    #[ORM\Column(enumType: StudentDegreeEnum::class)]
    private ?StudentDegreeEnum $degree = null;

    /**
     * @var Collection<int, StudentProfile>
     */
    #[ORM\OneToMany(targetEntity: StudentProfile::class, mappedBy: 'studentDegree')]
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
            // set the owning side to null (unless already changed)
            if ($studentProfile->getStudentDegree() === $this) {
                $studentProfile->setStudentDegree(null);
            }
        }

        return $this;
    }
}
