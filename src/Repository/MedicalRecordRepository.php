<?php

namespace App\Repository;

use App\Entity\MedicalRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedicalRecord>
 */
class MedicalRecordRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, MedicalRecord::class);
  }

  public function search($value = null)
  {
    return $this->createQueryBuilder('md')
      ->join('md.patient', 'p')
      ->where('p.cin LIKE :value')
      ->orWhere('p.firstName LIKE :value')
      ->orWhere('p.lastName LIKE :value')
      ->orWhere('md.title LIKE :value')
      ->orWhere('md.description LIKE :value')
      ->setParameter('value', "%$value%")
      // ->orderBy('md.date', 'DESC')
      ->getQuery()
      ->getResult();
  }
}
