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

  public function search($value = null,  $order = null): array
  {
    $order = $order ?: 'createdAt-DESC';
    [$col, $direction] = explode('-', $order);

    $qb = $this->createQueryBuilder('pr')
      ->join('pr.patient', 'p');

    if (!empty($value)) {
      $qb->where('p.cin LIKE :value')
        ->orWhere('p.firstName LIKE :value')
        ->orWhere('p.lastName LIKE :value')
        ->orWhere('pr.medicaments LIKE :value')
        ->setParameter('value', "%$value%");
    }

    $table = in_array($col, ['cin', 'firstName', 'lastName']) ? 'p' : 'pr';

    return $qb
      ->orderBy("$table.$col", $direction)
      ->getQuery()
      ->getResult();
  }
}
