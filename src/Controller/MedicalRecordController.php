<?php

namespace App\Controller;

use App\Entity\MedicalRecord;
use App\Form\MedicalRecordType;
use App\Repository\MedicalRecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Patient;
use DateTimeImmutable as Date;

#[Route('/medicalrecords')]
final class MedicalRecordController extends AbstractController
{
  #[Route(name: 'app_medicalrecords')]
  public function index(MedicalRecordRepository $mrr, Request $request): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $medicalRecords = $mrr->search($request->query->get('search'), $request->query->get('orderby'));
    return $this->render('medicalrecord/index.html.twig', [
      'medicalRecords' => $medicalRecords,
    ]);
  }

  #[Route('/{id}/add', name: 'medicalrecord_add')]
  public function new(Request $request, EntityManagerInterface $em, Patient $patient): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $medicalRecord = new MedicalRecord();
    $form = $this->createForm(MedicalRecordType::class, $medicalRecord, [
      'is_edit' => false
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $medicalRecord->setPatient($patient);
      $medicalRecord->setCreatedAt(new Date());
      $medicalRecord->setUpdatedAt(new Date());
      $em->persist($medicalRecord);
      $em->flush();
      $this->addFlash('success', "Medical record added for {$patient->getFirstName()} {$patient->getLastName()}");
      return $this->redirectToRoute('app_medicalrecords');
    }

    return $this->render('medicalrecord/add.html.twig', [
      'patient' => $patient,
      'form' => $form,
    ]);
  }

  #[Route('/{id}/edit', name: 'medicalrecord_edit')]
  public function edit(Request $request, MedicalRecord $medicalRecord, EntityManagerInterface $em): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $form = $this->createForm(MedicalRecordType::class, $medicalRecord, [
      'is_edit' => true
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $medicalRecord->setUpdatedAt(new Date());
      $em->flush();
      $this->addFlash('success', "Medical record updated for {$medicalRecord->getPatient()->getFirstName()} {$medicalRecord->getPatient()->getLastName()}");

      return $this->redirectToRoute('app_medicalrecords');
    }

    return $this->render('medicalrecord/edit.html.twig', [
      'medicalRecord' => $medicalRecord,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'medicalrecord_delete')]
  public function delete(Request $request, MedicalRecord $medicalRecord, EntityManagerInterface $em): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    if ($this->isCsrfTokenValid('delete_medicalrecord_' . $medicalRecord->getId(), $request->request->get('_token'))) {
      $em->remove($medicalRecord);
      $em->flush();
      $this->addFlash('success', "Medical record deleted successfully");
    }

    return $this->redirectToRoute('app_medicalrecords');
  }
}
