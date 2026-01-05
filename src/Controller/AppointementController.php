<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Appointement;
use App\Entity\Patient;
use App\Form\AppointementType;
use App\Repository\AppointementRepository;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use DateTimeImmutable as Date;

final class AppointementController extends AbstractController
{
  #[Route('/appointements', name: 'app_appointements')]
  public function index(AppointementRepository $ar, Request $request): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }

    $appointements = $ar->search(
      $request->query->get('search'),
      $request->query->get('orderby'),
      $request->query->get('from'),
      $request->query->get('to')
    );
    // dd($appointements);
    return $this->render('appointement/index.html.twig', compact('appointements'));
  }

  #[Route('/appointements/{id}/add', name: 'appointement_add')]
  public function create(Request $request, Patient $patient, EntityManagerInterface $em, AppointementRepository $ar, SettingsRepository $sr)
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $limit = $sr->find(1)->getLimitAppointements();
    $appointementsCount = $ar->countTodaysAppointements();
    if($appointementsCount === $limit){
      $this->addFlash('error',"Appointements limit($limit) for today reached");
      return $this->redirectToRoute("app_patients");
    }

    $appointement = new Appointement();
    $form = $this->createForm(AppointementType::class, $appointement, [
      'is_edit' => false
    ]);
    // dd($form);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $appointement->setPatient($patient);
      $appointement->setCreatedAt(new Date());
      $appointement->setUpdatedAt(new Date());
      $em->persist($appointement);
      $em->flush();

      $this->addFlash('success', "Appointement added  for '{$patient->getFirstName()} {$patient->getLastName()}'!");
      return $this->redirectToRoute('app_patients');
    }

    return $this->render('appointement/add.twig', [
      'form' => $form,
      'patient' => $patient
    ]);
  }

  #[Route('/appointements/{id}/edit', name: 'appointement_edit')]
  public function edit(Request $request, Appointement $appointement, EntityManagerInterface $em)
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $form = $this->createForm(AppointementType::class, $appointement, [
      'is_edit' => true
    ]);
    // dd($form);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $appointement->setUpdatedAt(new Date());
      $em->flush();

      $this->addFlash('success', "Appointement updated for {$appointement->getPatient()->getFirstName()} {$appointement->getPatient()->getLastName()}!");
      return $this->redirectToRoute('app_appointements');
    }

    return $this->render('appointement/edit.twig', [
      'form' => $form,
      'appointement' => $appointement
    ]);
  }

  #[Route('/appointements/{id}/delete', name: 'appointement_delete')]
  public function delete(
    Request $request,
    Appointement $appointement,
    EntityManagerInterface $em
  ): Response {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    if ($this->isCsrfTokenValid('delete_appointement_' . $appointement->getId(), $request->request->get('_token'))) {
      $em->remove($appointement);
      $em->flush();

      $this->addFlash('success', 'Appointement deleted successfully!');
    }

    return $this->redirectToRoute('app_appointements');
  }
}
