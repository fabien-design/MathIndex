<?php

namespace App\Twig\Extension;

use App\Entity\Course;
use App\Twig\Runtime\CourseExtensionRuntime;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CourseExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [CourseExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('allCourses', [$this, 'getCourses']),
        ];
    }

    public function getCourses()
    {
        $courses = $this->em->getRepository(Course::class)->findBy([], ['name' => 'ASC']);

        return $courses;
    }
}
