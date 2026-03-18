<?php

namespace App\Controller;

use App\Entity\StudentProfile;
use App\Repository\StudentProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/student-profiles')]
class StudentProfileController extends AbstractController
{
    #[Route('', name: 'app_student_profile_index', methods: ['GET'])]
    public function index(StudentProfileRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route('', name: 'app_student_profile_create', methods: ['POST'])]
    public function create(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $profile = $serializer->deserialize($request->getContent(), StudentProfile::class, 'json');

        $errors = $validator->validate($profile);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($profile);
        $em->flush();

        return $this->json($profile, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_student_profile_show', methods: ['GET'])]
    public function show(StudentProfile $profile): JsonResponse
    {
        return $this->json($profile, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_student_profile_update', methods: ['PATCH'])]
    public function update(
        StudentProfile $profile, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(), 
            StudentProfile::class, 
            'json', 
            ['object_to_populate' => $profile]
        );

        $em->flush();

        return $this->json($profile, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_student_profile_delete', methods: ['DELETE'])]
    public function delete(StudentProfile $profile, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($profile);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}