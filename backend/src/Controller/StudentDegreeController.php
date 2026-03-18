<?php

namespace App\Controller;

use App\Entity\StudentDegree;
use App\Repository\StudentDegreeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/student-degrees')]
class StudentDegreeController extends AbstractController
{
    /**
     * GET ALL : Liste tous les diplômes/parcours étudiants
     */
    #[Route('', name: 'app_student_degree_index', methods: ['GET'])]
    public function index(StudentDegreeRepository $repository): JsonResponse
    {
        return $this->json($repository->findBy([], ['startYear' => 'DESC']), Response::HTTP_OK);
    }

    /**
     * POST : Ajouter un nouveau parcours
     */
    #[Route('', name: 'app_student_degree_create', methods: ['POST'])]
    public function create(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $studentDegree = $serializer->deserialize($request->getContent(), StudentDegree::class, 'json');

            $errors = $validator->validate($studentDegree);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $em->persist($studentDegree);
            $em->flush();

            return $this->json($studentDegree, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Données invalides ou type de diplôme inconnu'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * GET ONE
     */
    #[Route('/{id}', name: 'app_student_degree_show', methods: ['GET'])]
    public function show(StudentDegree $studentDegree): JsonResponse
    {
        return $this->json($studentDegree, Response::HTTP_OK);
    }

    /**
     * PATCH : Mise à jour partielle
     */
    #[Route('/{id}', name: 'app_student_degree_update', methods: ['PATCH'])]
    public function update(
        StudentDegree $studentDegree, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(), 
            StudentDegree::class, 
            'json', 
            ['object_to_populate' => $studentDegree]
        );

        $em->flush();

        return $this->json($studentDegree, Response::HTTP_OK);
    }

    /**
     * DELETE
     */
    #[Route('/{id}', name: 'app_student_degree_delete', methods: ['DELETE'])]
    public function delete(StudentDegree $studentDegree, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($studentDegree);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}