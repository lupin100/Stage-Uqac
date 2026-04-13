<?php

namespace App\Controller;

use App\Entity\Contributor;
use App\Repository\ContributorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/contributors')]
class ContributorController extends AbstractController
{
    /**
     * GET ALL : Liste tous les contributeurs avec leurs publications
     */
    #[Route('', name: 'app_contributor_index', methods: ['GET'])]
    public function index(ContributorRepository $repository): JsonResponse
    {
        $contributors = $repository->findBy([], ['contributorOrder' => 'ASC']);

        return $this->json($contributors, Response::HTTP_OK, [], [
            'groups' => 'contributor:read'
        ]);
    }

    /**
     * POST : Créer un nouveau contributeur
     */
    #[Route('', name: 'app_contributor_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $contributor = $serializer->deserialize($request->getContent(), Contributor::class, 'json');

        $errors = $validator->validate($contributor);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($contributor);
        $em->flush();

        return $this->json($contributor, Response::HTTP_CREATED, [], [
            'groups' => 'contributor:read'
        ]);
    }

    /**
     * GET ONE : Récupère un contributeur par ID
     */
    #[Route('/{id}', name: 'app_contributor_show', methods: ['GET'])]
    public function show(Contributor $contributor): JsonResponse
    {
        return $this->json($contributor, Response::HTTP_OK, [], [
            'groups' => 'contributor:read'
        ]);
    }

    /**
     * PATCH : Mise à jour partielle
     */
    #[Route('/{id}', name: 'app_contributor_update', methods: ['PATCH'])]
    public function update(
        Contributor $contributor,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Contributor::class,
            'json',
            ['object_to_populate' => $contributor]
        );

        $em->flush();
        return $this->json($contributor, Response::HTTP_OK, [], [
            'groups' => 'contributor:read'
        ]);
    }

    /**
     * DELETE : Supprime un contributeur
     */
    #[Route('/{id}', name: 'app_contributor_delete', methods: ['DELETE'])]
    public function delete(Contributor $contributor, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($contributor);
        $em->flush();


        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
