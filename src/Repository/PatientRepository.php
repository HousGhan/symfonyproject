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

  public function search($value = null, $order = "createdAt-DESC"): array
  {
    $order = $order ?: "createdAt-DESC";
    [$col, $direction] = explode("-", $order);
    return $this->createQueryBuilder('p')
      ->where('p.cin LIKE :value')
      ->orWhere('p.firstName LIKE :value')
      ->orWhere('p.lastName LIKE :value')
      ->orWhere('p.phone LIKE :value')
      ->setParameter('value', "%$value%")
      ->orderBy("p.$col", $direction)
      ->getQuery()
      ->getResult()
    ;
  }
}
