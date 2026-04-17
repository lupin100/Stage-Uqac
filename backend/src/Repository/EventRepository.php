<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findEventsByFilters(?string $status, ?string $search, ?string $eventType = null, ?int $limit = null, ?int $offset = null): array
    {
        $qb = $this->createQueryBuilder('e');
        $now = new \DateTimeImmutable();

        // 1. Filtrage par Statut
        if ($status === 'upcoming') {
            $qb->andWhere('e.endDate > :now')->orderBy('e.startDate', 'ASC');
            $qb->setParameter('now', $now);
        } elseif ($status === 'past') {
            $qb->andWhere('e.endDate < :now')->orderBy('e.startDate', 'DESC');
            $qb->setParameter('now', $now);
        } else {
            $qb->orderBy('e.startDate', 'DESC');
        }

        // 2. Filtrage par Type d'événement (eventType)
        if ($eventType && $eventType !== 'all') {
            $qb->andWhere('e.eventType = :eventType')
               ->setParameter('eventType', $eventType);
        }

        // 3. Recherche par mot-clé (Levenshtein)
        if ($search) {
            $qb->andWhere('LEVENSHTEIN(e.title, :searchQuery) <= 4 OR e.title LIKE :likeQuery')
               ->addSelect('LEVENSHTEIN(e.title, :searchQuery) as HIDDEN score')
               ->setParameter('searchQuery', $search)
               ->setParameter('likeQuery', '%'.$search.'%')
               ->orderBy('score', 'ASC');
        }

        if ($limit) $qb->setMaxResults($limit);
        if ($offset) $qb->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    public function countEventsByFilters(?string $status, ?string $search, ?string $eventType = null): int
    {
        $qb = $this->createQueryBuilder('e')->select('count(e.id)');
        $now = new \DateTimeImmutable();

        if ($status === 'upcoming') {
            $qb->andWhere('e.endDate > :now')->setParameter('now', $now);
        } elseif ($status === 'past') {
            $qb->andWhere('e.endDate < :now')->setParameter('now', $now);
        }

        if ($eventType && $eventType !== 'all') {
            $qb->andWhere('e.eventType = :eventType')
               ->setParameter('eventType', $eventType);
        }

        if ($search) {
            $qb->andWhere('LEVENSHTEIN(e.title, :searchQuery) <= 4 OR e.title LIKE :likeQuery')
               ->setParameter('searchQuery', $search)
               ->setParameter('likeQuery', '%'.$search.'%');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
