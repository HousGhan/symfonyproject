<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Patient::class);
  }

  //    /**
  //     * @return Patient[] Returns an array of Patient objects
  //     */
  public function search($value = null): array
  {
    return $this->createQueryBuilder('p')
      ->andWhere('p.cin LIKE :value')
      ->orWhere('p.firstName LIKE :value')
      ->setParameter('value', "%$value%")
      ->getQuery()
      ->getResult()
    ;
  }

  //    public function findOneBySomeField($value): ?Patient
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
