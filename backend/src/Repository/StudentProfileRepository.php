<?php

namespace App\Repository;

use App\Entity\StudentProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentProfile>
 */
class StudentProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentProfile::class);
    }

    public function findDetailedByPersonRole(\App\Enum\PersonEnum $role): array
    {
        return $this->createQueryBuilder('sp')
            ->leftJoin('sp.person', 'p')
            ->leftJoin('p.institutions', 'i')
            ->leftJoin('sp.supervisor', 's')
            ->leftJoin('sp.coSupervisor', 'cs')
            ->leftJoin('sp.studentDegree', 'sd')
            ->andWhere('p.role = :role')
            ->setParameter('role', $role)
            ->select([
                'p.id AS id',
                "CONCAT(p.lastName, ', ', p.firstName) AS nom",
                'i.name AS universite',
                'sd.degree AS statut',
                "CONCAT(s.lastName, ', ', s.firstName) AS directeur",
                "CONCAT(cs.lastName, ', ', cs.firstName) AS coSuperviseur",
                'sp.topic AS topic',
                'sd.startYear AS start_year',
                'sd.endYear AS end_year',
            ])
            ->orderBy('p.lastName', 'ASC')
            ->addOrderBy('p.firstName', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    //    /**
    //     * @return StudentProfile[] Returns an array of StudentProfile objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?StudentProfile
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}