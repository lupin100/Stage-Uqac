<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/projects')]
class ProjectController extends AbstractController
{
    /**
     * GET ALL : Liste tous les projets
     */
    #[Route('', name: 'app_project_index', methods: ['GET'])]
    public function index(Request $request, ProjectRepository $repository): JsonResponse
    {
        $page = $request->query->get('page');


        if ($page === null) {
            return $this->json($repository->findAll(), Response::HTTP_OK);
        }

        $page = max(1, (int) $page);
        $limit = max(1, (int) $request->query->get('limit', 5)); // 5 projets par page par défaut
        $offset = ($page - 1) * $limit;

        $projects = $repository->findBy(
            [],
            ['id' => 'DESC'], // On affiche les plus récents en premier
            $limit,
            $offset
        );

        $total = $repository->count([]);

        return $this->json([
            'data'  => $projects,
            'page'  => $page,
            'limit' => $limit,
            'total' => $total,
            'last_page' => ceil($total / $limit)
        ]);
    }

    /**
     * POST : Créer un nouveau projet
     */
    #[Route('', name: 'app_project_create', methods: ['POST'])]
    public function create(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $project = $serializer->deserialize($request->getContent(), Project::class, 'json');

            $errors = $validator->validate($project);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $em->persist($project);
            $em->flush();

            return $this->json($project, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * GET ONE : Détails d'un projet
     */
    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): JsonResponse
    {
        return $this->json($project, Response::HTTP_OK);
    }

    /**
     * PATCH : Mise à jour partielle
     */
    #[Route('/{id}', name: 'app_project_update', methods: ['PATCH'])]
    public function update(
        Project $project, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(), 
            Project::class, 
            'json', 
            ['object_to_populate' => $project]
        );

        $em->flush();

        return $this->json($project, Response::HTTP_OK);
    }

    /**
     * DELETE : Supprimer un projet
     */
    #[Route('/{id}', name: 'app_project_delete', methods: ['DELETE'])]
    public function delete(Project $project, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($project);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}