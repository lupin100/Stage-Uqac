<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/events')]
class EventController extends AbstractController
{
    #[Route('', name: 'app_event_index', methods: ['GET'])]
    public function index(Request $request, EventRepository $repository): JsonResponse
    {
        $status = $request->query->get('status');
        $search = $request->query->get('q');
        $eventType = $request->query->get('thematic');

        $page = $request->query->get('page');
        $limit = (int) $request->query->get('limit', 10);

        if ($page === null) {
            $events = $repository->findEventsByFilters($status, $search, $eventType);
            return $this->json($events, Response::HTTP_OK);
        }

        $page = max(1, (int) $page);
        $offset = ($page - 1) * $limit;

        $events = $repository->findEventsByFilters($status, $search, $eventType, $limit, $offset);
        $total = $repository->countEventsByFilters($status, $search, $eventType);

        return $this->json([
            'data' => $events,
            'total' => $total,
            'page' => $page,
            'last_page' => (int) ceil($total / $limit),
            'filters' => [
                'status' => $status,
                'search' => $search,
                'thematic' => $eventType
            ]
        ], Response::HTTP_OK);
    }


    #[Route('', name: 'app_event_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $event = $serializer->deserialize($request->getContent(), Event::class, 'json');

            $errors = $validator->validate($event);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $em->persist($event);
            $em->flush();

            return $this->json($event, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Données invalides ou format de date incorrect.'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): JsonResponse
    {
        return $this->json($event, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_event_update', methods: ['PATCH'])]
    public function update(
        Event $event,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Event::class,
            'json',
            ['object_to_populate' => $event]
        );

        $em->flush();

        return $this->json($event, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['DELETE'])]
    public function delete(Event $event, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($event);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
