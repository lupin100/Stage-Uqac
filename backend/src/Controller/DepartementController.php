<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/departements')]
class DepartementController extends AbstractController
{
    /**
     * GET ALL : Liste tous les départements
     */
    #[Route('', name: 'app_departement_index', methods: ['GET'])]
    public function index(DepartementRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), Response::HTTP_OK, [], [
            'groups' => 'departement:read'
        ]);
    }

    /**
     * POST : Créer un département
     */
    #[Route('', name: 'app_departement_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $departement = $serializer->deserialize($request->getContent(), Departement::class, 'json');

        $errors = $validator->validate($departement);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($departement);
        $em->flush();

        return $this->json($departement, Response::HTTP_CREATED, [], [
            'groups' => 'departement:read'
        ]);
    }

    /**
     * GET ONE : Détails d'un département
     */
    #[Route('/{id}', name: 'app_departement_show', methods: ['GET'])]
    public function show(Departement $departement): JsonResponse
    {
        return $this->json($departement, Response::HTTP_OK, [], [
            'groups' => 'departement:read'
        ]);
    }

    /**
     * PATCH : Mise à jour partielle
     */
    #[Route('/{id}', name: 'app_departement_update', methods: ['PATCH'])]
    public function update(
        Departement $departement,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Departement::class,
            'json',
            ['object_to_populate' => $departement]
        );

        $em->flush();

        return $this->json($departement, Response::HTTP_OK, [], [
            'groups' => 'departement:read'
        ]);
    }

    /**
     * DELETE
     */
    #[Route('/{id}', name: 'app_departement_delete', methods: ['DELETE'])]
    public function delete(Departement $departement, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($departement);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
