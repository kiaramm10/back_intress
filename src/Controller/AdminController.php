<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }


    #[Route('/admin/create-master', name: 'admin_create_master')]
    public function createMaster(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $user->addRole('ROLE_MASTER'); // agregar el rol ROLE_MASTER al usuario

        // Solo los usuarios con el rol ROLE_ADMIN pueden otorgar el rol de ROLE_MASTER
        if ($this->isGranted('ROLE_ADMIN')) {
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('admin_home');
        } else {
            return $this->redirectToRoute('personal_home');
        }
    }

    return $this->render('admin/create_master.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
    }

    #[Route('/admin/create-admin', name: 'admin_create_admin', requirements: ['_role' => 'ROLE_ADMIN'])]
    public function createAdmin(): Response
    {
        // Lógica para crear un nuevo usuario con el rol de Admin
        return $this->render('admin/create_admin.html.twig');
    }
}
