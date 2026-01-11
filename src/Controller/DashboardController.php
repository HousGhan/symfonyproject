<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AppointementRepository;
use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
#[IsGranted("ROLE_DOCTOR")]
final class DashboardController extends AbstractController
{
  #[Route(name: "app_dashboard")]
  public function index(AppointementRepository $ar, PatientRepository $pr): Response
  {

    $monthlyData = $ar->getMonthlyPrices();

    $labels = array_column($monthlyData, 'month');
    $data = array_column($monthlyData, 'totalPrice');
    // dd($data,$labels);
    $totalRevenue = $ar->getTotalRevenue();
    $todaysAppointements = $ar->countTodaysAppointements();
    $totalAppointements = $ar->totalAppointements();
    $totalPatients = $pr->totalPatients();
    // dd($totalRevenue, $todaysAppointements);
    return $this->render('dashboard/index.html.twig', [
      'totalRevenue' => $totalRevenue,
      'todaysAppointements' => $todaysAppointements,
      'totalAppointements' => $totalAppointements,
      'totalPatients' => $totalPatients,
      'labels' => $labels,
      'data' => $data,
    ]);
  }
}
