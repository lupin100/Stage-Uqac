<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findStudentsBySupervisor(int $supervisorId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.studentProfile', 'sp')
            ->leftJoin('sp.studentDegree', 'sd')
            ->where('sp.supervisor = :id')
            ->orWhere('sp.coSupervisor = :id')
            ->setParameter('id', $supervisorId)
            ->select([
                'p.id AS id_person',
                'p.firstName AS firstName',
                'p.lastName AS lastName',
                'p.role AS role',
                'sp.id AS id_studentProfile',
                'sp.topic AS topic',
                'sd.degree AS degree',
                'sd.startYear AS startYear'
            ])
            ->getQuery()
            ->getResult();
    }

    public function findDetailedPersonById(int $id): ?array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.studentProfile', 'sp')
            ->leftJoin('sp.studentDegree', 'sd')
            ->leftJoin('sp.supervisor', 's')
            ->leftJoin('sp.coSupervisor', 'cs')
            ->leftJoin('p.institutions', 'i')
            ->leftJoin('p.departements', 'd')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->select([
                'p.id AS id',
                'p.firstName AS firstName',
                'p.lastName AS lastName',
                'p.email AS email',
                'p.photoPath AS photoPath',
                'p.jobTitle AS jobTitle',
                'p.personalPageUrl AS personalPageUrl',
                'p.biography AS biography',
                'p.role AS role',

                'sp.id AS studentProfileId',
                'sp.topic AS topic',

                'sd.degree AS degree',
                'sd.startYear AS start_year',
                'sd.endYear AS end_year',

                's.id AS supervisor_id',
                's.firstName AS supervisor_first_name',
                's.lastName AS supervisor_last_name',

                'cs.id AS co_supervisor_id',
                'cs.firstName AS co_supervisor_first_name',
                'cs.lastName AS co_supervisor_last_name',

                'i.name AS institution_name',
                'd.name AS departement_name',
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Person[] Returns an array of Person objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Person
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
