<?php

namespace App\Twig\Extension;

use App\Entity\Course;
use App\Twig\Runtime\CourseExtensionRuntime;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class CourseExtension extends AbstractExtension
{
    public function __construct(private EntityManagerInterface $em)
    {
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

    public function getCourses(): array
    {
        return $courses = $this->em->getRepository(Course::class)->findBy([], ['name' => 'ASC']);
    }
}
