<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Rule;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as Unique;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[Unique(
  fields: ['cin'],
  message: 'This CIN already exists'
)]
class Patient
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Rule\NotBlank(message: "Required")]
  private ?string $firstName = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Rule\NotBlank(message: "Required")]
  private ?string $lastName = null;

  #[ORM\Column(length: 255)]
  #[Rule\NotBlank(message: "Required")]
  private ?string $cin = null;

  #[ORM\Column(nullable: true)]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\Column(nullable: true)]
  private ?\DateTimeImmutable $updatedAt = null;

  /**
   * @var Collection<int, Appointement>
   */
  #[ORM\OneToMany(targetEntity: Appointement::class, mappedBy: 'patient', orphanRemoval: true)]
  private Collection $appointements;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $createdBy = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Rule\NotBlank(message: "Required")]
  private ?string $phone = null;

  /**
   * @var Collection<int, MedicalRecord>
   */
  #[ORM\OneToMany(targetEntity: MedicalRecord::class, mappedBy: 'patient', orphanRemoval: true)]
  private Collection $medicalRecords;

  /**
   * @var Collection<int, Prescription>
   */
  #[ORM\OneToMany(targetEntity: Prescription::class, mappedBy: 'patient', orphanRemoval: true)]
  private Collection $prescriptions;

  public function __construct()
  {
    $this->appointements = new ArrayCollection();
    $this->medicalRecords = new ArrayCollection();
    $this->prescriptions = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(?string $firstName): static
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(?string $lastName): static
  {
    $this->lastName = $lastName;

    return $this;
  }

  public function getCin(): ?string
  {
    return $this->cin;
  }

  public function setCin(?string $cin): static
  {
    $this->cin = $cin;

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

  /**
   * @return Collection<int, Appointement>
   */
  public function getAppointements(): Collection
  {
    return $this->appointements;
  }

  public function addAppointement(Appointement $appointement): static
  {
    if (!$this->appointements->contains($appointement)) {
      $this->appointements->add($appointement);
      $appointement->setPatient($this);
    }

    return $this;
  }

  public function removeAppointement(Appointement $appointement): static
  {
    if ($this->appointements->removeElement($appointement)) {
      // set the owning side to null (unless already changed)
      if ($appointement->getPatient() === $this) {
        $appointement->setPatient(null);
      }
    }

    return $this;
  }

  public function getCreatedBy(): ?string
  {
    return $this->createdBy;
  }

  public function setCreatedBy(?string $createdBy): static
  {
    $this->createdBy = $createdBy;

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

  /**
   * @return Collection<int, MedicalRecord>
   */
  public function getMedicalRecords(): Collection
  {
      return $this->medicalRecords;
  }

  public function addMedicalRecord(MedicalRecord $medicalRecord): static
  {
      if (!$this->medicalRecords->contains($medicalRecord)) {
          $this->medicalRecords->add($medicalRecord);
          $medicalRecord->setPatient($this);
      }

      return $this;
  }

  public function removeMedicalRecord(MedicalRecord $medicalRecord): static
  {
      if ($this->medicalRecords->removeElement($medicalRecord)) {
          // set the owning side to null (unless already changed)
          if ($medicalRecord->getPatient() === $this) {
              $medicalRecord->setPatient(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Prescription>
   */
  public function getPrescriptions(): Collection
  {
      return $this->prescriptions;
  }

  public function addPrescription(Prescription $prescription): static
  {
      if (!$this->prescriptions->contains($prescription)) {
          $this->prescriptions->add($prescription);
          $prescription->setPatient($this);
      }

      return $this;
  }

  public function removePrescription(Prescription $prescription): static
  {
      if ($this->prescriptions->removeElement($prescription)) {
          // set the owning side to null (unless already changed)
          if ($prescription->getPatient() === $this) {
              $prescription->setPatient(null);
          }
      }

      return $this;
  }
}
