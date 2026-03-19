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
     * GET ALL : Liste toutes les publications, classées par année (plus récent d'abord)
     * Supporte la pagination via les paramètres "page" et "limit"
     */
    #[Route('', name: 'app_publication_index', methods: ['GET'])]
    public function index(Request $request, PublicationRepository $repository): JsonResponse
    {
        $page = $request->query->get('page');

        // pas de page précisé -> envoie toutes les données
        if ($page === null) {
            $publications = $repository->findBy([], ['year' => 'DESC']);
            return $this->json($publications, Response::HTTP_OK);
        }

        // avec page spec -> pagination
        $page = max(1, (int) $page);
        $limit = max(1, (int) $request->query->get('limit', 10));
        $offset = ($page - 1) * $limit;

        $publications = $repository->findBy(
            [],
            ['year' => 'DESC'],
            $limit,
            $offset
        );

        $total = $repository->count([]);

        return $this->json([
            'data' => $publications,
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ]);
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

            return $this->json($publication, Response::HTTP_CREATED);
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
        return $this->json($publication, Response::HTTP_OK);
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

        return $this->json($publication, Response::HTTP_OK);
    }

    /**
     * DELETE
     */
    #[Route('/{id}', name: 'app_publication_delete', methods: ['DELETE'])]
    public function delete(Publication $publication, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($publication);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}