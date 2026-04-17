<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Publication>
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    public function findPublicationsByFilters(?int $year, ?string $type, ?string $search, ?int $limit = null, ?int $offset = null): array
    {
        $qb = $this->createQueryBuilder('p');

        if ($year) {
            $qb->andWhere('p.year = :year')
               ->setParameter('year', $year);
        }

        if ($type && $type !== 'all') {
            $qb->andWhere('p.publicationType = :type')
               ->setParameter('type', $type);
        }

        if ($search) {
            $qb->andWhere('LEVENSHTEIN(p.title, :searchQuery) <= 4 OR p.title LIKE :likeQuery')
               ->addSelect('LEVENSHTEIN(p.title, :searchQuery) as HIDDEN score')
               ->setParameter('searchQuery', $search)
               ->setParameter('likeQuery', '%'.$search.'%')
               ->orderBy('score', 'ASC');
        } else {
            $qb->orderBy('p.year', 'DESC')
               ->addOrderBy('p.id', 'DESC');
        }

        if ($limit) $qb->setMaxResults($limit);
        if ($offset) $qb->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    public function countPublicationsByFilters(?int $year, ?string $type, ?string $search): int
    {
        $qb = $this->createQueryBuilder('p')->select('count(p.id)');

        if ($year) {
            $qb->andWhere('p.year = :year')->setParameter('year', $year);
        }

        if ($type && $type !== 'all') {
            $qb->andWhere('p.publicationType = :type')->setParameter('type', $type);
        }

        if ($search) {
            $qb->andWhere('LEVENSHTEIN(p.title, :searchQuery) <= 4 OR p.title LIKE :likeQuery')
               ->setParameter('searchQuery', $search)
               ->setParameter('likeQuery', '%'.$search.'%');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
