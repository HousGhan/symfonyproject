<?php

namespace App\Repository;

use App\Entity\Appointement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable as Date;

/**
 * @extends ServiceEntityRepository<Appointement>
 */
class AppointementRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Appointement::class);
  }

  public function search(
    $value = null,
    $order = null,
    $from = null,
    $to = null
  ) {
    $order = $order ?: "createdAt-DESC";
    [$col, $direction] = explode('-', $order);

    $query = $this->createQueryBuilder('a')
      ->join('a.patient', 'p');


    if (!empty($value)) {
      $query
        ->where('p.cin LIKE :value')
        ->orWhere('p.firstName LIKE :value')
        ->orWhere('p.lastName LIKE :value')
        ->setParameter('value', "%$value%");
    }

    if (!empty($to) && !empty($from)) {
      $query->orWhere('a.date BETWEEN :from AND :to')
        ->setParameter('from', $from)
        ->setParameter('to', $to);
    }

    $table = in_array($col, ['cin', 'firstName', 'lastName']) ? 'p' : 'a';

    return $query
      ->orderBy("$table.$col", $direction)
      ->getQuery()
      ->getResult();
  }

  public function countTodaysAppointements()
  {
    $start = new Date('today 00:00:00');
    $end = new Date('tomorrow 00:00:00');

    return $this->createQueryBuilder('a')
      ->select('COUNT(a.id)')
      ->where('a.date BETWEEN :start AND :end')
      ->setParameter('start', $start)
      ->setParameter('end', $end)
      ->getQuery()
      ->getSingleScalarResult();
  }

  public function getTotalRevenue(): float
  {
    return (float) $this->createQueryBuilder('a')
      ->select('SUM(a.price)')
      ->where('a.payed = :payed')
      ->setParameter('payed', true)
      ->getQuery()
      ->getSingleScalarResult();
  }

  public function totalAppointements()
  {
    return $this->createQueryBuilder('a')
      ->select('COUNT(a.id)')
      ->getQuery()
      ->getSingleScalarResult();
  }

  public function getMonthlyPrices(): array
  {
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
    SELECT DATE_FORMAT(created_at, "%Y-%m") AS month, SUM(price) AS totalPrice
    FROM appointement
    GROUP BY month
    ORDER BY month ASC
';

    $stmt = $conn->prepare($sql);
    $result = $stmt->executeQuery();
    return $result->fetchAllAssociative();
  }
}
