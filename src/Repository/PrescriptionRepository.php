<?php

namespace App\Repository;

use App\Entity\Prescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prescription>
 */
class PrescriptionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Prescription::class);
  }

  public function search($value = null): array
  {
    return $this->createQueryBuilder('pr')
      ->join('pr.patient', 'p')
      ->where('p.cin LIKE :value')
      ->orWhere('p.firstName LIKE :value')
      ->orWhere('p.lastName LIKE :value')
      ->setParameter('value', "%$value%")
      ->orderBy("pr.createdAt", "DESC")
      ->getQuery()
      ->getResult()
    ;
  }
}
