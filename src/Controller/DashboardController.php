<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DashboardController extends AbstractController
{
  #[Route('/dashboard', name: 'app_dashboard')]
  public function index(): Response
  {
    $user = $this->getUser();
    if (!in_array("ROLE_DOCTOR", $user->getRoles())) {
      return $this->redirectToRoute("app_patients");
    }
    return $this->render('dashboard/index.html.twig');
  }
}
