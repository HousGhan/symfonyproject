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
    $u1 = new User();
    $u1->setFirstName($f->firstName);
    $u1->setLastName($f->lastName);
    $u1->setEmail("h@h.com");

    $ph = $this->ph->hashPassword($u1, '123');
    $u1->setPassword($ph);

    $u1->setRoles(["ROLE_DOCTOR"]);
    $u1->setCreatedAt(new Date());
    $u1->setUpdatedAt(new Date());

    $manager->persist($u1);

    $u2 = new User();
    $u2->setFirstName($f->firstName);
    $u2->setLastName($f->lastName);
    $u2->setEmail("a@a.com");

    $ph = $this->ph->hashPassword($u2, '456');
    $u2->setPassword($ph);

    $u2->setRoles(["ROLE_SECRETARY"]);
    $u2->setCreatedAt(new Date());
    $u2->setUpdatedAt(new Date());

    $manager->persist($u2);

    $u3 = new User();
    $u3->setFirstName($f->firstName);
    $u3->setLastName($f->lastName);
    $u3->setEmail("n@n.com");

    $ph = $this->ph->hashPassword($u3, '789');
    $u3->setPassword($ph);

    $u3->setRoles(["ROLE_USER"]);
    $u3->setCreatedAt(new Date());
    $u3->setUpdatedAt(new Date());

    $manager->persist($u3);

    $manager->flush();
  }
}
