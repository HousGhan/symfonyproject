<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Form\SettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SettingsRepository;

final class SettingController extends AbstractController
{
  #[Route('/settings', name: 'app_settings')]
  public function index(Request $request, EntityManagerInterface $em, SettingsRepository $sr): Response
  {

    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }

    if ($u) {
      if (in_array('ROLE_SECRETARY', $u->getRoles())) {
        return $this->redirectToRoute('app_patients');
      }
    }

    $settings = $sr->find(1);

    if (!$settings) {
      $settings = new Settings();
    }

    $form = $this->createForm(SettingsType::class, $settings);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->persist($settings);
      $em->flush();

      $this->addFlash('success', 'Settings updated successfully');

      return $this->redirectToRoute('app_patients');
    }

    return $this->render('setting/index.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
