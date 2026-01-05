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

  public function getRevenueData(string $interval = 'month'): array
  {
    $conn = $this->getEntityManager()->getConnection();
    $now = new \DateTimeImmutable('now');

    switch ($interval) {
      case 'week':
        $start = $now->modify('-6 days')->format('Y-m-d 00:00:00');
        $groupBy = 'DATE(date)';
        $select = 'DATE(date) AS date';
        break;

      case 'month':
        $start = $now->modify('first day of this month')->format('Y-m-d 00:00:00');
        $groupBy = 'DATE(date)';
        $select = 'DATE(date) AS date';
        break;

      case 'year':
        $start = $now->modify('first day of January')->format('Y-m-d 00:00:00');
        $groupBy = "DATE_FORMAT(date, '%Y-%m-01')"; // first day of month
        $select = "DATE_FORMAT(date, '%Y-%m-01') AS date"; // returns '2026-01-01', '2026-02-01', ...
        break;

      default:
        $start = $now->modify('first day of this month')->format('Y-m-d 00:00:00');
        $groupBy = 'DATE(date)';
        $select = 'DATE(date) AS date';
    }

    $sql = "SELECT $select, SUM(price) AS revenue
            FROM appointement
            WHERE payed = 1 AND date >= '$start'
            GROUP BY $groupBy
            ORDER BY $groupBy ASC";

    $stmt = $conn->prepare($sql);
    $result = $stmt->executeQuery();

    return $result->fetchAllAssociative();
  }
}
