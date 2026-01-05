<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, User::class);
  }

  /**
   * Used to upgrade (rehash) the user's password automatically over time.
   */
  public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
  {
    if (!$user instanceof User) {
      throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
    }

    $user->setPassword($newHashedPassword);
    $this->getEntityManager()->persist($user);
    $this->getEntityManager()->flush();
  }

  public function search($value = null, $order = null)
  {
    $order = $order ?: 'createdAt-DESC';
    [$col, $direction] = explode('-', $order);

    $qb = $this->createQueryBuilder('u');

    if (!empty($value)) {
      $qb->where('u.email LIKE :value')
        ->orWhere('u.firstName LIKE :value')
        ->orWhere('u.lastName LIKE :value')
        ->setParameter('value', "%$value%");
    }

    return $qb
      ->orderBy("u.$col", $direction)
      ->getQuery()
      ->getResult();
  }
}
