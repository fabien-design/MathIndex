<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Skill::class, mappedBy: 'Course')]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'Course', targetEntity: Thematic::class)]
    private Collection $thematics;

    #[ORM\OneToMany(mappedBy: 'Course', targetEntity: Exercise::class)]
    private Collection $exercises;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->thematics = new ArrayCollection();
        $this->exercises = new ArrayCollection();
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

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->addCourse($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            $skill->removeCourse($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Thematic>
     */
    public function getThematics(): Collection
    {
        return $this->thematics;
    }

    public function addThematic(Thematic $thematic): static
    {
        if (!$this->thematics->contains($thematic)) {
            $this->thematics->add($thematic);
            $thematic->setCourse($this);
        }

        return $this;
    }

    public function removeThematic(Thematic $thematic): static
    {
        if ($this->thematics->removeElement($thematic)) {
            // set the owning side to null (unless already changed)
            if ($thematic->getCourse() === $this) {
                $thematic->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exercise>
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): static
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises->add($exercise);
            $exercise->setCourse($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): static
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getCourse() === $this) {
                $exercise->setCourse(null);
            }
        }

        return $this;
    }
}