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
    public function index(PersonRepository $repository): JsonResponse
    {
        $persons = $repository->findBy([], ['lastName' => 'ASC']);

        return $this->json($persons, Response::HTTP_OK, [], [
            'groups' => 'person:read'
        ]);
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
