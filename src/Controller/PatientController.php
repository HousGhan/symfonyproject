<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use App\Form\PatientType;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable as Date;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class PatientController extends AbstractController
{
  #[Route('/patients', name: 'app_patients')]
  public function index(PatientRepository $pr): Response
  {
    $patients = $pr->findAll();
    return $this->render('patient/index.html.twig', compact('patients'));
  }

  #[Route('/patients/add', name: 'patient_add')]
  public function create(Request $request, EntityManagerInterface $em)
  {
    $patient = new Patient();
    $form = $this->createForm(PatientType::class, $patient, [
      'is_edit' => false
    ]);
    // dd($form);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $patient->setCreatedAt(new Date());
      $patient->setUpdatedAt(new Date());
      $em->persist($patient);
      $em->flush();

      $this->addFlash('success', 'Patient added successfully!');
      return $this->redirectToRoute('app_patients');
    }

    return $this->render('patient/add.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/patients/{id}/edit', name: 'patient_edit')]
  public function edit(
    Request $request,
    Patient $patient,
    EntityManagerInterface $em
  ): Response {

    $form = $this->createForm(PatientType::class, $patient, [
      'is_edit' => true
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $patient->setUpdatedAt(new Date());

      $em->flush();

      $this->addFlash('success', 'Patient updated successfully!');
      return $this->redirectToRoute('app_patients');
    }

    return $this->render('patient/edit.twig', [
      'form' => $form,
      'patient' => $patient,
    ]);
  }

  #[Route('/patients/{id}/delete', name: 'patient_delete')]
  public function delete(
    Request $request,
    Patient $patient,
    EntityManagerInterface $em
  ): Response {

    if ($this->isCsrfTokenValid('delete_patient_' . $patient->getId(), $request->request->get('_token'))) {
      $em->remove($patient);
      $em->flush();

      $this->addFlash('success', 'Patient deleted successfully!');
    }

    return $this->redirectToRoute('app_patients');
  }
}
