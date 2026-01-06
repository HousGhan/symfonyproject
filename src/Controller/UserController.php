<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTimeImmutable as Date;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_DOCTOR")]
final class UserController extends AbstractController
{
  #[Route('/users', name: 'app_users')]
  public function index(UserRepository $ur, Request $request): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $users = $ur->search($request->query->get('search'), $request->query->get('orderby'));
    // dd($users);
    return $this->render('user/index.html.twig', compact('users'));
  }

  #[Route('/users/add', name: 'user_add')]
  public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $user = new User();
    $form = $this->createForm(UserType::class, $user, [
      'is_edit' => false
    ]);
    // dd($form);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
      $user->setCreatedAt(new Date());
      $user->setUpdatedAt(new Date());

      $em->persist($user);
      $em->flush();

      $this->addFlash('success', 'User added successfully!');
      return $this->redirectToRoute('app_users');
    }

    return $this->render('user/add.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/users/{id}/edit', name: 'user_edit')]
  public function edit(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher, User $user)
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    $form = $this->createForm(UserType::class, $user, [
      'is_edit' => true
    ]);
    // dd($form);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
      $user->setUpdatedAt(new Date());

      $em->persist($user);
      $em->flush();

      $this->addFlash('success', 'User updated successfully!');
      return $this->redirectToRoute('app_users');
    }

    return $this->render('user/add.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/users/{id}/delete', name: 'user_delete')]
  public function delete(Request $request, EntityManagerInterface $em, User $user)
  {
    $u = $this->getUser();
    if (!$u) {
      return $this->redirectToRoute("app_login");
    }
    if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('_token'))) {
      $em->remove($user);
      $em->flush();

      $this->addFlash('success', 'User deleted successfully!');
    }

    return $this->redirectToRoute('app_users');
  }
}
