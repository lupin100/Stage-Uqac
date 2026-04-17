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
     * GET ALL : Liste tous les projets filtrés avec leurs contributeurs
     */
    #[Route('', name: 'app_project_index', methods: ['GET'])]
    public function index(Request $request, ProjectRepository $repository): JsonResponse
    {
        $status = $request->query->get('status');
        $thematic = $request->query->get('thematic');
        $search = $request->query->get('q');

        $page = $request->query->get('page');
        $limit = (int) $request->query->get('limit', 5);

        $context = ['groups' => 'project:read'];

        if ($page === null) {
            $projects = $repository->findProjectsByFilters($status, $search, $thematic);
            return $this->json($projects, Response::HTTP_OK, [], $context);
        }

        $page = max(1, (int) $page);
        $offset = ($page - 1) * $limit;

        $projects = $repository->findProjectsByFilters($status, $search, $thematic, $limit, $offset);
        $total = $repository->countProjectsByFilters($status, $search, $thematic);

        return $this->json([
            'data'  => $projects,
            'total' => $total,
            'page'  => $page,
            'limit' => $limit,
            'last_page' => ceil($total / $limit),
            'filters' => [
                'status' => $status,
                'thematic' => $thematic,
                'search' => $search
            ]
        ], Response::HTTP_OK, [], $context);
    }

    #[Route('/thematics', name: 'app_project_thematics', methods: ['GET'])]
    public function getThematics(ProjectRepository $repository): JsonResponse
    {
        $results = $repository->findDistinctThematics();
        
        // On transforme le tableau d'objets [['thematic' => 'Santé'], ...] 
        // en un tableau simple de chaînes ['Santé', ...]
        $thematics = array_column($results, 'thematic');

        return $this->json($thematics);
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

            return $this->json($project, Response::HTTP_CREATED, [], ['groups' => 'project:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * GET ONE : Détails d'un projet spécifique
     */
    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): JsonResponse
    {
        return $this->json($project, Response::HTTP_OK, [], ['groups' => 'project:read']);
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

        return $this->json($project, Response::HTTP_OK, [], ['groups' => 'project:read']);
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
