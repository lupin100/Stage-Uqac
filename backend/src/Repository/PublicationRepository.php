<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Publication>
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    public function findPublicationsByFilters(?int $year, ?string $type, ?string $search, ?int $limit = null, ?int $offset = null): Paginator
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.contributors', 'c')
            ->addSelect('c')
            ->leftJoin('c.person', 'pers')
            ->addSelect('pers')
            ->orderBy('p.year', 'DESC');

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

        $query = $qb->getQuery();

        $paginator = new Paginator($query, true);

        return $paginator;
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

        public function findDistinctYears(): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.year')
            ->where('p.year IS NOT NULL')
            ->orderBy('p.year', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findDistinctTypes(): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.publicationType')
            ->where('p.publicationType IS NOT NULL')
            ->orderBy('p.publicationType', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
