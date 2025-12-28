<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;
use DateTimeImmutable as Date;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  private UserPasswordHasherInterface $ph;

  public function __construct(UserPasswordHasherInterface $ph)
  {
    $this->ph = $ph;
  }
  public function load(ObjectManager $manager): void
  {
    $f = Factory::create("en_US");
    $u = new User();
    $u->setFirstName($f->firstName);
    $u->setLastName($f->lastName);
    $u->setEmail($f->email);

    $ph = $this->ph->hashPassword($u, '123');
    $u->setPassword($ph);

    $u->setRoles(["ROLE_DOCTOR"]);
    $u->setCreatedAt(new Date());
    $u->setUpdatedAt(new Date());

    $manager->persist($u);

    $manager->flush();
  }
}
