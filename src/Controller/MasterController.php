<?php

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MasterController extends AbstractController
{
    #[Route('/master', name: 'master')]
    public function index(): Response
    {
        return $this->render('master/index.html.twig');
    }

    #[Route('/master/assign-role', name: 'master_assign_role', methods: ['POST'])]
    public function assignRole(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->request->get('userId');
        $newRole = $request->request->get('newRole');

        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('El usuario no fue encontrado.');
        }

        // Actualizar los roles del usuario y persistir en la base de datos
        $roles = $user->getRoles();
        $roles[] = $newRole;
        $user->setRoles($roles);
        $entityManager->flush();

        return $this->redirectToRoute('master');
    }
}

