<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Rule;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
class Settings
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(nullable: true)]
  #[Rule\PositiveOrZero(message: "Has to be 0 or more")]
  private ?int $limitAppointements = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $phone = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $address = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $cabinet = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $email = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $specialty = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $doctor = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getLimitAppointements(): ?int
  {
    return $this->limitAppointements;
  }

  public function setLimitAppointements(?int $limitAppointements): static
  {
    $this->limitAppointements = $limitAppointements;

    return $this;
  }

  public function getPhone(): ?string
  {
    return $this->phone;
  }

  public function setPhone(?string $phone): static
  {
    $this->phone = $phone;

    return $this;
  }

  public function getAddress(): ?string
  {
    return $this->address;
  }

  public function setAddress(?string $address): static
  {
    $this->address = $address;

    return $this;
  }

  public function getCabinet(): ?string
  {
    return $this->cabinet;
  }

  public function setCabinet(?string $cabinet): static
  {
    $this->cabinet = $cabinet;

    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(?string $email): static
  {
    $this->email = $email;

    return $this;
  }

  public function getSpecialty(): ?string
  {
    return $this->specialty;
  }

  public function setSpecialty(?string $specialty): static
  {
    $this->specialty = $specialty;

    return $this;
  }

  public function getDoctor(): ?string
  {
      return $this->doctor;
  }

  public function setDoctor(?string $doctor): static
  {
      $this->doctor = $doctor;

      return $this;
  }
}
