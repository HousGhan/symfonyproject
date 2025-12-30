<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Appointement;
use App\Entity\Patient;
use App\Repository\AppointementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AppointementController extends AbstractController
{
  #[Route('/appointements', name: 'app_appointements')]
  public function index(AppointementRepository $ar): Response
  {
    $appointements = $ar->findAll();
    return $this->render('appointement/index.html.twig', compact('appointements'));
  }

  #[Route('/appointements/{id}/add', name: 'appointement_add')]
  public function create(Request $request, Patient $patient, EntityManagerInterface $em)
  {
    dd($patient);
  }
}
