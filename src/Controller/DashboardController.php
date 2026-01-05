<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AppointementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
final class DashboardController extends AbstractController
{
  #[Route(name: "app_dashboard")]
  public function index(AppointementRepository $ar, Request $request): Response
  {

    return $this->render('dashboard/index.html.twig');
  }
}
