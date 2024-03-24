<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Course::class, inversedBy: 'skills')]
    #[ORM\JoinTable(name: 'skill_courses')]
    private Collection $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
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
     * @return Collection<int, course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static // Correction ici
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->addSkill($this); // Ajout pour maintenir la relation dans les deux sens
        }

        return $this;
    }

    public function removeCourse(Course $course): static // Correction ici
    {
        if ($this->courses->removeElement($course)) {
            $course->removeSkill($this); // Ajout pour maintenir la relation dans les deux sens
        }

        return $this;
    }
}
