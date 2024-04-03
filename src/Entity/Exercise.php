<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
#[Vich\Uploadable]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thematic $thematic = null;

    #[ORM\Column(length: 255)]
    private ?string $chapter = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $keywords = null;

    #[ORM\Column]
    private ?int $difficulty = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Origin $origin = null;

    #[ORM\Column(length: 255)]
    private ?string $originName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $originInformation = null;

    #[ORM\Column(length: 255)]
    private ?string $proposedByType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proposedByFirstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proposedByLasName = null;

    /**
     * @var string A "Y-m-d H:i:s" formatted value
     */
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    #[Assert\NotBlank(groups: ['new'], message: 'Le fichier du sujet est obligatoire')]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $exerciseFile = null;

    #[Assert\NotBlank(groups: ['new'], message: 'Le fichier de correction est obligatoire')]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $correctionFile = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->createdAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): static
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getThematic(): ?Thematic
    {
        return $this->thematic;
    }

    public function setThematic(?Thematic $thematic): static
    {
        $this->thematic = $thematic;

        return $this;
    }

    public function getChapter(): ?string
    {
        return $this->chapter;
    }

    public function setChapter(string $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getOrigin(): ?Origin
    {
        return $this->origin;
    }

    public function setOrigin(?Origin $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getOriginName(): ?string
    {
        return $this->originName;
    }

    public function setOriginName(string $originName): static
    {
        $this->originName = $originName;

        return $this;
    }

    public function getOriginInformation(): ?string
    {
        return $this->originInformation;
    }

    public function setOriginInformation(string $originInformation): static
    {
        $this->originInformation = $originInformation;

        return $this;
    }

    public function getProposedByType(): ?string
    {
        return $this->proposedByType;
    }

    public function setProposedByType(string $proposedByType): static
    {
        $this->proposedByType = $proposedByType;

        return $this;
    }

    public function getProposedByFirstName(): ?string
    {
        return $this->proposedByFirstName;
    }

    public function setProposedByFirstName(string $proposedByFirstName): static
    {
        $this->proposedByFirstName = $proposedByFirstName;

        return $this;
    }

    public function getProposedByLasName(): ?string
    {
        return $this->proposedByLasName;
    }

    public function setProposedByLasName(string $proposedByLasName): static
    {
        $this->proposedByLasName = $proposedByLasName;

        return $this;
    }

    public function getExerciseFile(): ?File
    {
        return $this->exerciseFile;
    }

    public function setExerciseFile(?File $exerciseFile): static
    {
        $this->exerciseFile = $exerciseFile;

        return $this;
    }

    public function getCorrectionFile(): ?File
    {
        return $this->correctionFile;
    }

    public function setCorrectionFile(file $correctionFile): static
    {
        $this->correctionFile = $correctionFile;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}
