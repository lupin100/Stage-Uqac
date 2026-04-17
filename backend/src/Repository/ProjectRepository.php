<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findProjectsByFilters(?string $status, ?string $search, ?string $thematic, ?int $limit = null, ?int $offset = null): Paginator
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.contributors', 'c')
        ->addSelect('c')
        ->leftJoin('c.person', 'pers')
        ->addSelect('pers');

        if ($status === 'finished') {
            $qb->andWhere('p.isFinished = true');
        } elseif ($status === 'ongoing') {
            $qb->andWhere('p.isFinished = false');
        }

        if ($thematic && $thematic !== 'all') {
            $qb->andWhere('p.thematic = :thematic')
               ->setParameter('thematic', $thematic);
        }

        if ($search) {
            $qb->andWhere('LEVENSHTEIN(p.title, :searchQuery) <= 4 OR p.title LIKE :likeQuery')
               ->addSelect('LEVENSHTEIN(p.title, :searchQuery) as HIDDEN score')
               ->setParameter('searchQuery', $search)
               ->setParameter('likeQuery', '%'.$search.'%')
               ->orderBy('score', 'ASC');
        } else {
            $qb->orderBy('p.id', 'DESC');
        }

        if ($limit) $qb->setMaxResults($limit);
        if ($offset) $qb->setFirstResult($offset);

        return new Paginator($qb->getQuery(), true);
    }

    public function countProjectsByFilters(?string $status, ?string $search, ?string $thematic): int
    {
        $qb = $this->createQueryBuilder('p')->select('count(p.id)');

        if ($status === 'finished') {
            $qb->andWhere('p.isFinished = true');
        } elseif ($status === 'ongoing') {
            $qb->andWhere('p.isFinished = false');
        }

        if ($thematic && $thematic !== 'all') {
            $qb->andWhere('p.thematic = :thematic')->setParameter('thematic', $thematic);
        }

        if ($search) {
            $qb->andWhere('LEVENSHTEIN(p.title, :searchQuery) <= 4 OR p.title LIKE :likeQuery')
               ->setParameter('searchQuery', $search)
               ->setParameter('likeQuery', '%'.$search.'%');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findDistinctThematics(): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.thematic')
            ->where('p.thematic IS NOT NULL')
            ->orderBy('p.thematic', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
