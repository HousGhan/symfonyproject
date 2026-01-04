<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Rule;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'prescriptions')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Patient $patient = null;

  #[ORM\Column(nullable: true)]
  #[Rule\NotBlank(message: "Required")]
  private ?string $medicaments = null;

  #[ORM\Column(nullable: true)]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\Column(nullable: true)]
  private ?\DateTimeImmutable $updatedAt = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getPatient(): ?Patient
  {
    return $this->patient;
  }

  public function setPatient(?Patient $patient): static
  {
    $this->patient = $patient;

    return $this;
  }

  public function getMedicaments(): ?string
  {
    return $this->medicaments;
  }

  public function setMedicaments(?string $medicaments): static
  {
    $this->medicaments = $medicaments;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(?\DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): ?\DateTimeImmutable
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }
}
