<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/persons')]
class PersonController extends AbstractController
{
    /**
     * GET ALL : Liste toutes les personnes avec leurs relations
     */
    #[Route('', name: 'app_person_index', methods: ['GET'])]
    public function index(Request $request, PersonRepository $repository): JsonResponse
    {
        $page = $request->query->get('page');
        $role = $request->query->get('role'); // On récupère le rôle s'il est fourni

        $criteria = [];
        if ($role) {
            $criteria['role'] = $role;
        }

        if ($page === null) {
            $persons = $repository->findBy($criteria, ['lastName' => 'ASC']);
            return $this->json($persons, Response::HTTP_OK, [], ['groups' => 'person:read']);
        }

        $page = max(1, (int) $page);
        $limit = max(1, (int) $request->query->get('limit', 6));
        $offset = ($page - 1) * $limit;

        $persons = $repository->findBy(
            $criteria,
            ['lastName' => 'ASC'],
            $limit,
            $offset
        );

        $total = $repository->count($criteria);

        return $this->json([
            'data' => $persons,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'last_page' => ceil($total / $limit)
        ], Response::HTTP_OK, [], ['groups' => 'person:read']);
    }

    #[Route('/details/{id}', name: 'app_person_details', methods: ['GET'])]
    public function details(int $id, PersonRepository $repository): JsonResponse
    {
        $person = $repository->findDetailedPersonById($id);

        if (!$person) {
            return $this->json(['error' => 'Personne introuvable'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $person['id'],
            'firstName' => $person['firstName'],
            'lastName' => $person['lastName'],
            'email' => $person['email'],
            'photoPath' => $person['photoPath'],
            'jobTitle' => $person['jobTitle'],
            'personalPageUrl' => $person['personalPageUrl'],
            'biography' => $person['biography'],
            'role' => $person['role'],

            'institution' => [
                'name' => $person['institution_name'] ?: null,
            ],

            'departement' => [
                'name' => $person['departement_name'] ?: null,
            ],

            'studentProfile' => [
                'id' => $person['studentProfileId'],
                'topic' => $person['topic'],
                'studentDegree' => [
                    'degree' => $person['degree'],
                    'startYear' => $person['start_year'],
                    'endYear' => $person['end_year'],
                ],
                'supervisor' => [
                    'id' => $person['supervisor_id'],
                    'firstName' => $person['supervisor_first_name'],
                    'lastName' => $person['supervisor_last_name'],
                ],
                'coSupervisor' => [
                    'id' => $person['co_supervisor_id'],
                    'firstName' => $person['co_supervisor_first_name'],
                    'lastName' => $person['co_supervisor_last_name'],
                ],
            ],
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/{id}/students', name: 'api_person_students', methods: ['GET'])]
    public function getSupervisedStudents(int $id, PersonRepository $personRepository): JsonResponse
    {
        $supervisor = $personRepository->find($id);
        
        if (!$supervisor) {
            return $this->json(['error' => 'Membre introuvable'], 404);
        }

        $students = $personRepository->findStudentsBySupervisor($id);

        return $this->json($students);
    }

    /**
     * GET : Filtrer les membres par rôle de manière générique
     */
    #[Route('/filter/{role}', name: 'app_person_filter_by_role', methods: ['GET'])]
    public function getByRole(string $role, PersonRepository $repository): JsonResponse
    {
        // On tente de convertir la chaîne de l'URL en cas de l'Enum
        // Ex: "Membre associé" deviendra PersonEnum::MEMBRE_ASSOCIE
        $enumRole = \App\Enum\PersonEnum::tryFrom($role);

        if (!$enumRole) {
            return $this->json([
                'error' => 'Rôle non valide',
                'received' => $role,
                'available_roles' => array_column(\App\Enum\PersonEnum::cases(), 'value')
            ], Response::HTTP_BAD_REQUEST);
        }

        $members = $repository->findBy(
            ['role' => $enumRole],
            ['lastName' => 'ASC']
        );

        return $this->json($members, Response::HTTP_OK, [], ['groups' => 'person:read']);
    }

    /**
     * POST : Créer une nouvelle personne
     */
    #[Route('', name: 'app_person_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $person = $serializer->deserialize($request->getContent(), Person::class, 'json');


            $errors = $validator->validate($person);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $em->persist($person);
            $em->flush();

            return $this->json($person, Response::HTTP_CREATED, [], [
                'groups' => 'person:read'
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Données invalides : ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * GET ONE : Détails d'une personne
     */
    #[Route('/{id}', name: 'app_person_show', methods: ['GET'])]
    public function show(Person $person): JsonResponse
    {
        return $this->json($person, Response::HTTP_OK, [], [
            'groups' => 'person:read'
        ]);
    }

    /**
     * PATCH : Mise à jour partielle
     */
    #[Route('/{id}', name: 'app_person_update', methods: ['PATCH'])]
    public function update(
        Person $person,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Person::class,
            'json',
            ['object_to_populate' => $person]
        );

        $em->flush();

        return $this->json($person, Response::HTTP_OK, [], [
            'groups' => 'person:read'
        ]);
    }

    /**
     * DELETE : Suppression
     */
    #[Route('/{id}', name: 'app_person_delete', methods: ['DELETE'])]
    public function delete(Person $person, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($person);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}