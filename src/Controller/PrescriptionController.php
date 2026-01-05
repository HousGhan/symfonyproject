<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Prescription;
use App\Entity\Patient;
use App\Repository\PrescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\PrescriptionType;
use DateTimeImmutable as Date;

#[Route('/prescriptions')]
final class PrescriptionController extends AbstractController
{
  #[Route(name: 'app_prescriptions')]
  public function index(PrescriptionRepository $pr, Request $request): Response
  {
    $prescriptions = array_map(function ($p) {
      $rows = array_values(array_filter(
        array_map('trim', explode("\n", $p->getMedicaments())),
        fn($row) => $row !== ''
      ));
      $p->parsed = $rows;
      return $p;
    }, $pr->search($request->query->get('search')));
    // dd($prescriptions);
    return $this->render('prescription/index.html.twig', [
      'prescriptions' => $prescriptions,
    ]);
  }

  #[Route('/{id}/add', name: 'prescription_add')]
  public function new(Request $request, EntityManagerInterface $em, Patient $patient): Response
  {
    $prescription = new Prescription();
    $form = $this->createForm(PrescriptionType::class, $prescription, [
      'is_edit' => false
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $prescription->setPatient($patient);
      $prescription->setCreatedAt(new Date());
      $prescription->setUpdatedAt(new Date());
      $em->persist($prescription);
      $em->flush();
      // dd($prescription->getId());
      $this->addFlash('success', "Prescription added for {$patient->getFirstName()} {$patient->getLastName()}");
      return $this->redirectToRoute('app_prescriptions');
    }

    return $this->render('prescription/add.html.twig', [
      'patient' => $patient,
      'form' => $form,
    ]);
  }

  #[Route('/{id}/pdf', name: 'generate_pdf')]
  public function generatePdf(Prescription $prescription): Response
  {
    $user = $this->getUser();
    $options = new Options();
    $options->set('defaultFont', 'Verdana');
    $dompdf = new Dompdf($options);

    $html = $this->renderView('prescription/pdf.twig', [
      "prescription" => $prescription,
      "user" => $user
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF to browser
    return new Response(
      $dompdf->output(),
      200,
      [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="prescription.pdf"'
      ]
    );
  }

  #[Route('/{id}/edit', name: 'prescription_edit')]
  public function edit(Request $request, Prescription $prescription, EntityManagerInterface $em): Response
  {
    $form = $this->createForm(PrescriptionType::class, $prescription, [
      'is_edit' => true
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $prescription->setUpdatedAt(new Date());
      $em->flush();
      $this->addFlash('success', "Prescription updated for {$prescription->getPatient()->getFirstName()} {$prescription->getPatient()->getLastName()}");

      return $this->redirectToRoute('app_prescriptions');
    }

    return $this->render('prescription/edit.html.twig', [
      'prescription' => $prescription,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'prescription_delete')]
  public function delete(Request $request, Prescription $prescription, EntityManagerInterface $em): Response
  {
    if ($this->isCsrfTokenValid('delete_prescription_' . $prescription->getId(), $request->request->get('_token'))) {
      $em->remove($prescription);
      $em->flush();
      $this->addFlash('success', "Prescription deleted successfully");
    }

    return $this->redirectToRoute('app_prescriptions');
  }
}
