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
    private Collection $course;

    public function __construct()
    {
        $this->course = new ArrayCollection();
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
    public function getCourse(): Collection
    {
        return $this->course;
    }

    public function addCourse(course $Course): static
    {
        if (!$this->course->contains($Course)) {
            $this->course->add($Course);
        }

        return $this;
    }

    public function removeCourse(course $Course): static
    {
        $this->course->removeElement($Course);

        return $this;
    }
}
