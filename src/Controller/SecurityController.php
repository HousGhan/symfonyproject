<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
  #[Route(path: '/', name: 'app_login')]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    $error = $authenticationUtils->getLastAuthenticationError();

    $lastUsername = $authenticationUtils->getLastUsername();

    $user = $this->getUser();
    // dd($user);

    if ($user) {
      if (in_array('ROLE_DOCTOR', $user->getRoles())) {
        return $this->redirectToRoute('app_dashboard');
      } elseif (in_array('ROLE_SECRETARY', $user->getRoles())) {
        return $this->redirectToRoute('app_patients');
      } else {
        return $this->redirectToRoute("app_login");
      }
    }

    return $this->render('security/login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
  }

  #[Route(path: '/logout', name: 'app_logout')]
  public function logout(): void
  {
    throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }
}
