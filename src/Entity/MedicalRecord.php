<?php

namespace App\Entity;

use App\Repository\MedicalRecordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Rule;

#[ORM\Entity(repositoryClass: MedicalRecordRepository::class)]
class MedicalRecord
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'medicalRecords')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Patient $patient = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Rule\NotBlank(message: "Required")]
  private ?string $title = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  #[Rule\NotBlank(message: "Required")]
  private ?string $description = null;

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

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(?string $title): static
  {
    $this->title = $title;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;

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
