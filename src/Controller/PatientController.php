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


#[Route("/patients")]
final class PatientController extends AbstractController
{
  #[Route(name: 'app_patients')]
  public function index(PatientRepository $pr, Request $request): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $patients = $pr->search($request->query->get('search'), $request->query->get('orderby'));
    return $this->render('patient/index.html.twig', compact('patients'));
  }

  #[Route('/add', name: 'patient_add')]
  public function create(Request $request, EntityManagerInterface $em)
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $patient = new Patient();
    $form = $this->createForm(PatientType::class, $patient, [
      'is_edit' => false
    ]);
    // dd($form);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var \App\Entity\User $user */
      $user = $this->getUser();
      $patient->setCreatedBy($user->getFullName());
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

  #[Route('/{id}', name: "patient_show")]
  public function show(Patient $patient)
  {
    // dd($patient);
    return $this->render("patient/show.html.twig", ['patient' => $patient]);
  }

  #[Route('/{id}/edit', name: 'patient_edit')]
  public function edit(
    Request $request,
    Patient $patient,
    EntityManagerInterface $em
  ): Response {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $form = $this->createForm(PatientType::class, $patient, [
      'is_edit' => true
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var \App\Entity\User $user */
      $user = $this->getUser();
      $patient->setCreatedBy($user->getFullName());
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

  #[Route('/{id}/delete', name: 'patient_delete')]
  public function delete(
    Request $request,
    Patient $patient,
    EntityManagerInterface $em
  ): Response {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    if ($this->isCsrfTokenValid('delete_patient_' . $patient->getId(), $request->request->get('_token'))) {
      $em->remove($patient);
      $em->flush();

      $this->addFlash('success', 'Patient deleted successfully!');
    }

    return $this->redirectToRoute('app_patients');
  }
}
