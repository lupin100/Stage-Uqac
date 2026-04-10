<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/news')]
class NewsController extends AbstractController
{
    /**
     * GET ALL : Liste des actualités, les plus récentes en premier
     * Supporte la pagination via les paramètres "page" et "limit"
     */
    #[Route('', name: 'app_news_index', methods: ['GET'])]
    public function index(Request $request, NewsRepository $repository): JsonResponse
    {
        $page = $request->query->get('page');

        // pas de page précisé -> envoie toutes les données
        if ($page === null) {
            $news = $repository->findBy([], ['publishedAt' => 'DESC']);
            return $this->json($news, Response::HTTP_OK);
        }

        // avec page spec -> pagination
        $page = max(1, (int) $page);
        $limit = max(1, (int) $request->query->get('limit', 10));
        $offset = ($page - 1) * $limit;

        $news = $repository->findBy(
            [],
            ['publishedAt' => 'DESC'],
            $limit,
            $offset
        );

        $total = $repository->count([]);

        return $this->json([
            'data' => $news,
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ]);
    }

    /**
     * POST : Créer une actualité
     */
    #[Route('', name: 'app_news_create', methods: ['POST'])]
    public function create(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $news = $serializer->deserialize($request->getContent(), News::class, 'json');

            $errors = $validator->validate($news);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $em->persist($news);
            $em->flush();

            return $this->json($news, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Format de données invalide'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * GET ONE
     */
    #[Route('/{id}', name: 'app_news_show', methods: ['GET'])]
    public function show(News $news): JsonResponse
    {
        return $this->json($news, Response::HTTP_OK);
    }

    /**
     * PATCH : Mise à jour partielle
     */
    #[Route('/{id}', name: 'app_news_update', methods: ['PATCH'])]
    public function update(
        News $news, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(), 
            News::class, 
            'json', 
            ['object_to_populate' => $news]
        );

        $em->flush();

        return $this->json($news, Response::HTTP_OK);
    }

    /**
     * DELETE
     */
    #[Route('/{id}', name: 'app_news_delete', methods: ['DELETE'])]
    public function delete(News $news, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($news);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}