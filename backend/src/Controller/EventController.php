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
    public function index(EventRepository $repository): JsonResponse
    {
        // On récupère les événements triés par date de début
        return $this->json($repository->findBy([], ['startDate' => 'DESC']), Response::HTTP_OK);
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