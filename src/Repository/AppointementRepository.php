<?php

namespace App\Repository;

use App\Entity\Appointement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointement>
 */
class AppointementRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Appointement::class);
  }

  public function search($value = null, $order = "date-DESC")
  {
    $order = $order ?: "date-DESC";
    [$col, $direction] = explode('-', $order);

    $qb = $this->createQueryBuilder('a')
      ->join('a.patient', 'p');

    if (!empty($value)) {
      $qb->andWhere(
        $qb->expr()->orX(
          'p.cin LIKE :value',
          'p.firstName LIKE :value',
          'p.lastName LIKE :value'
        )
      )
        ->setParameter('value', "%$value%");
    }

    $table = in_array($col, ['cin', 'firstName', 'lastName']) ? 'p' : 'a';

    return $qb
      ->orderBy("$table.$col", $direction)
      ->getQuery()
      ->getResult();
  }
}
