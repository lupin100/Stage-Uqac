<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/publications')]
class PublicationController extends AbstractController
{
    /**
     * GET ALL : Liste toutes les publications avec leur contributeur
     */
    #[Route('', name: 'app_publication_index', methods: ['GET'])]
    public function index(Request $request, PublicationRepository $repository): JsonResponse
    {
        $year = $request->query->get('year') ? (int) $request->query->get('year') : null;
        $type = $request->query->get('type');
        $search = $request->query->get('q');

        $page = $request->query->get('page');
        $limit = max(1, (int) $request->query->get('limit', 10));

        if ($page === null) {
            $publications = $repository->findPublicationsByFilters($year, $type, $search);
            return $this->json($publications, Response::HTTP_OK, [], ['groups' => 'publication:read']);
        }

        $page = max(1, (int) $page);
        $offset = ($page - 1) * $limit;

        $publications = $repository->findPublicationsByFilters($year, $type, $search, $limit, $offset);
        $total = $repository->countPublicationsByFilters($year, $type, $search);

        return $this->json([
            'data' => $publications,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'last_page' => ceil($total / $limit),
            'filters' => [
                'year' => $year,
                'type' => $type,
                'search' => $search
            ]
        ], Response::HTTP_OK, [], ['groups' => 'publication:read']);
    }

    #[Route('/details', name: 'app_publication_details', methods: ['GET'])]
    public function getDetails(Request $request, PublicationRepository $repository): JsonResponse
    {
        $page = $request->query->get('page');

        // Pas de pagination : on récupère tout avec les détails
        if ($page === null) {
            $publications = $repository->findAllWithDetails();
            return $this->json($publications, Response::HTTP_OK, [], ['groups' => 'publication:read']);
        }

        // Avec pagination
        $page = max(1, (int) $page);
        $limit = max(1, (int) $request->query->get('limit', 10));
        $offset = ($page - 1) * $limit;

        // On passe les arguments à la méthode qui les accepte maintenant
        $publications = $repository->findAllWithDetails($limit, $offset);
        $total = $repository->count([]);

        return $this->json([
            'data' => $publications,
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ], Response::HTTP_OK, [], ['groups' => 'publication:read']);
    }

    /**
     * POST : Créer une publication
     */
    #[Route('', name: 'app_publication_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $publication = $serializer->deserialize($request->getContent(), Publication::class, 'json');

            $errors = $validator->validate($publication);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $em->persist($publication);
            $em->flush();

            return $this->json($publication, Response::HTTP_CREATED, [], ['groups' => 'publication:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * GET ONE
     */
    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication): JsonResponse
    {
        return $this->json($publication, Response::HTTP_OK, [], ['groups' => 'publication:read']);
    }

    /**
     * PATCH : Mise à jour partielle
     */
    #[Route('/{id}', name: 'app_publication_update', methods: ['PATCH'])]
    public function update(
        Publication $publication,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Publication::class,
            'json',
            ['object_to_populate' => $publication]
        );

        $em->flush();

        return $this->json($publication, Response::HTTP_OK, [], ['groups' => 'publication:read']);
    }

    /**
     * DELETE
     */
    #[Route('/{id}', name: 'app_publication_delete', methods: ['DELETE'])]
    public function delete(Publication $publication, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($publication);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
