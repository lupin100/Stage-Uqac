<?php

namespace App\Entity;

use App\Enum\PersonEnum;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import indispensable

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['person:read', 'publication:read', 'project:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 2500, nullable: true)]
    #[Groups(['person:read'])]
    private ?string $biography = null;

    #[ORM\Column]
    #[Groups(['person:read'])]
    private ?bool $isActive = true;

    #[ORM\Column]
    #[Groups(['person:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['person:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 100)]
    #[Groups(['person:read', 'publication:read', 'project:read'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Groups(['person:read', 'publication:read', 'project:read'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Groups(['person:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['person:read'])]
    private ?string $photoPath = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Groups(['person:read'])]
    private ?string $jobTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['person:read'])]
    private ?string $personalPageUrl = null;

    #[ORM\Column(enumType: PersonEnum::class)]
    #[Groups(['person:read'])]
    private ?PersonEnum $role = null;



    #[ORM\OneToOne(inversedBy: 'person', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['person:read'])]
    private ?StudentProfile $studentProfile = null;

    #[ORM\OneToOne(mappedBy: 'person', cascade: ['persist', 'remove'])]
    #[Groups(['person:read'])]
    private ?Contributor $contributor = null;

    #[ORM\OneToMany(mappedBy: 'supervisor', targetEntity: StudentProfile::class)]
    #[Groups(['person:read'])]
    private Collection $supervisedStudents;

    #[ORM\OneToMany(mappedBy: 'coSupervisor', targetEntity: StudentProfile::class)]
    #[Groups(['person:read'])]
    private Collection $coSupervisedStudents;

    /**
     * @var Collection<int, Departement>
     */
    #[ORM\ManyToMany(targetEntity: Departement::class, inversedBy: 'persons')]
    #[Groups(['person:read'])]
    private Collection $departements;

    /**
     * @var Collection<int, Institution>
     */
    #[ORM\ManyToMany(targetEntity: Institution::class, inversedBy: 'persons')]
    #[Groups(['person:read'])]
    private Collection $institutions;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->supervisedStudents = new ArrayCollection();
        $this->coSupervisedStudents = new ArrayCollection();
        $this->departements = new ArrayCollection();
        $this->institutions = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(?string $photoPath): static
    {
        $this->photoPath = $photoPath;
        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    public function getPersonalPageUrl(): ?string
    {
        return $this->personalPageUrl;
    }

    public function setPersonalPageUrl(?string $personalPageUrl): static
    {
        $this->personalPageUrl = $personalPageUrl;
        return $this;
    }

    public function getRole(): ?PersonEnum
    {
        return $this->role;
    }

    public function setRole(PersonEnum $role): static
    {
        $this->role = $role;
        return $this;
    }


    public function getStudentProfile(): ?StudentProfile
    {
        return $this->studentProfile;
    }

    public function setStudentProfile(?StudentProfile $studentProfile): static
    {
        $this->studentProfile = $studentProfile;
        return $this;
    }

    public function getContributor(): ?Contributor
    {
        return $this->contributor;
    }

    public function setContributor(?Contributor $contributor): static
    {
        // unset the owning side of the relation if necessary
        if ($contributor === null && $this->contributor !== null) {
            $this->contributor->setPerson(null);
        }

        // set the owning side of the relation if necessary
        if ($contributor !== null && $contributor->getPerson() !== $this) {
            $contributor->setPerson($this);
        }

        $this->contributor = $contributor;

        return $this;
    }

    /**
     * @return Collection<int, StudentProfile>
     */
    public function getSupervisedStudents(): Collection
    {
        return $this->supervisedStudents;
    }

    public function addSupervisedStudent(StudentProfile $studentProfile): static
    {
        if (!$this->supervisedStudents->contains($studentProfile)) {
            $this->supervisedStudents->add($studentProfile);
            $studentProfile->setSupervisor($this);
        }
        return $this;
    }

    public function removeSupervisedStudent(StudentProfile $studentProfile): static
    {
        if ($this->supervisedStudents->removeElement($studentProfile)) {
            if ($studentProfile->getSupervisor() === $this) {
                $studentProfile->setSupervisor(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, StudentProfile>
     */
    public function getCoSupervisedStudents(): Collection
    {
        return $this->coSupervisedStudents;
    }

    public function addCoSupervisedStudent(StudentProfile $studentProfile): static
    {
        if (!$this->coSupervisedStudents->contains($studentProfile)) {
            $this->coSupervisedStudents->add($studentProfile);
            $studentProfile->setCoSupervisor($this);
        }
        return $this;
    }

    public function removeCoSupervisedStudent(StudentProfile $studentProfile): static
    {
        if ($this->coSupervisedStudents->removeElement($studentProfile)) {
            if ($studentProfile->getCoSupervisor() === $this) {
                $studentProfile->setCoSupervisor(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Departement>
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): static
    {
        if (!$this->departements->contains($departement)) {
            $this->departements->add($departement);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): static
    {
        $this->departements->removeElement($departement);

        return $this;
    }

    /**
     * @return Collection<int, Institution>
     */
    public function getInstitutions(): Collection
    {
        return $this->institutions;
    }

    public function addInstitution(Institution $institution): static
    {
        if (!$this->institutions->contains($institution)) {
            $this->institutions->add($institution);
        }

        return $this;
    }

    public function removeInstitution(Institution $institution): static
    {
        $this->institutions->removeElement($institution);

        return $this;
    }
}
