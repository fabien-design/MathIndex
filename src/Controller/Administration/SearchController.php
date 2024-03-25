<?php

namespace App\Controller\Administration;

use App\Repository\ClassroomRepository;
use App\Repository\CourseRepository;
use App\Repository\ExerciseRepository;
use App\Repository\SkillRepository;
use App\Repository\ThematicRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    #[Route('/administration/search', name: 'app_administration_search', methods: ['GET'])]
    public function search(Request $request, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, CourseRepository $courseRepository, ClassroomRepository $classroomRepository, ThematicRepository $thematicRepository, SkillRepository $skillRepository, ExerciseRepository $exerciseRepository): JsonResponse
    {
        $query = $request->query->get('query');
        $entity = $request->query->get('entity');

        if (null !== $query && '' !== $query) {
            $query = htmlspecialchars($query);
            $query = strip_tags($query);
            $html = '';
            switch ($entity) {

                case 'user':
                    $values = $userRepository->findByName($query);
                    if (empty($values)) {
                        $html = '<tr><td colspan="2" class="text-center text-lg p-4">Aucun contributeur "'.$query.'" n\'a été trouvée.</td></tr>';
                    }
                    foreach ($values as $item) {
                        $roles = $item->getRoles();
                        $roleLabel = '';
                
                        if (in_array('ROLE_ADMIN', $roles)) {
                            $roleLabel = 'Administrateur';
                        } elseif (in_array('ROLE_TEACHER', $roles)) {
                            $roleLabel = 'Enseignant';
                        } elseif (in_array('ROLE_STUDENT', $roles)) {
                            $roleLabel = 'Étudiant';
                        }
                
                        $html .= '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-element-id="'.$item->getId().'">
                        <td scope="row" class="px-6 py-4">'.$item->getLastname().'</td>
                        <td scope="row" class="px-6 py-4">'.$item->getFirstname().'</td>
                            <td scope="row" class="px-6 py-4">'.$roleLabel.'</td>
                            <td scope="row" class="px-6 py-4">'.$item->getEmail().'</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-4">
                                    <a href="'.$urlGenerator->generate('app_administration_user_edit', ['id' => $item->getId()]).'" class="font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2"><i class="fa-solid fa-pen-to-square"></i>Modifier</a>
                                    <button class="open-delete-modal font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2" data-modal-target="popup-modal" data-modal-toggle="popup-modal" data-modal-element-id="'.$item->getId().'" ><i class="fa-solid fa-trash-can"></i>Supprimer</button>
                                </div>
                            </td>
                        </tr>';
                    }
                    break;
                

                case 'exercise':
                    $values = $exerciseRepository->findByName($query);
                    if (empty($values)) {
                        $html = '<tr><td colspan="12" class="text-center text-lg p-4">Aucun exercice "'.$query.'" n\'a été trouvée.</td></tr>';
                    }
                    foreach ($values as $item) {
                        $html .= '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-element-id="'.$item->getId().'">
                            <td scope="row" class="px-6 py-4">'.$item->getName().'</td>
                            <td scope="row" class="px-6 py-4">'.$item->getName().'</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-4">
                                    <a href="'.$urlGenerator->generate('app_administration_exercise_edit', ['id' => $item->getId()]).'" class="font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2"><i class="fa-solid fa-pen-to-square"></i>Modifier</a>
                                    <button class="open-delete-modal font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2" data-modal-target="popup-modal" data-modal-toggle="popup-modal" data-modal-element-id="'.$item->getId().'" ><i class="fa-solid fa-trash-can"></i>Supprimer</button>
                                </div>
                            </td>
                        </tr>';
                    }
                    break;

                case 'course':
                    $values = $courseRepository->findByName($query);
                    if (empty($values)) {
                        $html = '<tr><td colspan="2" class="text-center text-lg p-4">Aucune matière trouvée.</td></tr>';
                    }
                    foreach ($values as $item) {
                        $html .= '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-element-id="'.$item->getId().'">
                            <td scope="row" class="px-6 py-4">
                                '.$item->getName().'
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-4">
                                    <a href="'.$urlGenerator->generate('app_administration_course_edit', ['id' => $item->getId()]).'" class="font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2"><i class="fa-solid fa-pen-to-square"></i>Modifier</a>
                                    <button class="open-delete-modal font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2" data-modal-target="popup-modal" data-modal-toggle="popup-modal" data-modal-element-id="'.$item->getId().'" ><i class="fa-solid fa-trash-can"></i>Supprimer</button>
                                </div>
                            </td>
                        </tr>';
                    }
                    break;

                case 'classroom':
                    $values = $classroomRepository->findByName($query);
                    if (empty($values)) {
                        $html = '<tr><td colspan="2" class="text-center text-lg p-4">Aucune classe "'.$query.'" n\'a été trouvée.</td></tr>';
                    }
                    foreach ($values as $item) {
                        $html .= '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-element-id="'.$item->getId().'">
                            <td scope="row" class="px-6 py-4">'.$item->getName().'</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-4">
                                    <a href="'.$urlGenerator->generate('app_administration_classroom_edit', ['id' => $item->getId()]).'" class="font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2"><i class="fa-solid fa-pen-to-square"></i>Modifier</a>
                                    <button class="open-delete-modal font-medium text-neutral-500 dark:text-neutral-300 hover:underline flex items-start gap-2" data-modal-target="popup-modal" data-modal-toggle="popup-modal" data-modal-element-id="'.$item->getId().'" ><i class="fa-solid fa-trash-can"></i>Supprimer</button>
                                </div>
                            </td>
                        </tr>';
                    }
                    break;
                case 'thematic':
                    $values = $thematicRepository->findBy(['name' => $query]);
                    break;
                case 'skill':
                    $values = $skillRepository->findBy(['name' => $query]);
                    break;
                default:
                    $values = $userRepository->findBy(['firstname' => $query]);
                    break;
            }

            return new JsonResponse(['values' => $html], Response::HTTP_OK);
        } else {
            return new JsonResponse(['error' => 'No query parameter provided'], Response::HTTP_BAD_REQUEST);
        }
    }
}
