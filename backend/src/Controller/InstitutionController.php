<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Repository\InstitutionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/institutions')]
class InstitutionController extends AbstractController
{
    #[Route('', name: 'app_institution_index', methods: ['GET'])]
    public function index(InstitutionRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route('', name: 'app_institution_create', methods: ['POST'])]
    public function create(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $institution = $serializer->deserialize($request->getContent(), Institution::class, 'json');

        $errors = $validator->validate($institution);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($institution);
        $em->flush();

        return $this->json($institution, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_institution_show', methods: ['GET'])]
    public function show(Institution $institution): JsonResponse
    {
        return $this->json($institution, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_institution_update', methods: ['PATCH'])]
    public function update(
        Institution $institution, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(), 
            Institution::class, 
            'json', 
            ['object_to_populate' => $institution]
        );

        $em->flush();

        return $this->json($institution, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_institution_delete', methods: ['DELETE'])]
    public function delete(Institution $institution, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($institution);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}