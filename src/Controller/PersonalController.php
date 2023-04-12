<?php

namespace App\Controller;

use App\Entity\Personal;
use App\Form\PersonalType;
use App\Repository\PersonalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PersonalController extends AbstractController
{
    #[Route('/persona', name: 'personal_home', requirements:['_role' => 'ROLE_USER'])]
    public function index(): Response
    {
        return $this->render('personal/index.html.twig', [
            'personals' => $personalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_personal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonalRepository $personalRepository): Response
    {
        $personal = new Personal();
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalRepository->save($personal, true);

            return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal/new.html.twig', [
            'personal' => $personal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personal_show', methods: ['GET'])]
    public function show(Personal $personal): Response
    {
        return $this->render('personal/show.html.twig', [
            'personal' => $personal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personal $personal, PersonalRepository $personalRepository): Response
    {
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalRepository->save($personal, true);

            return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal/edit.html.twig', [
            'personal' => $personal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personal_delete', methods: ['POST'])]
    public function delete(Request $request, Personal $personal, PersonalRepository $personalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personal->getId(), $request->request->get('_token'))) {
            $personalRepository->remove($personal, true);
        }

        return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/personal/holidays', name: 'personal_holidays', requirements: ['_role' => 'ROLE_USER'])]
    public function holidays(): Response
    {
        // Lógica para mostrar las vacaciones del usuario
        return $this->render('personal/holidays.html.twig');
    }

    #[Route('/personal/documents', name: 'personal_documents', requirements: ['_role' => 'ROLE_USER'])]
    public function documents(): Response
    {
        // Lógica para mostrar los documentos del usuario
        return $this->render('personal/documents.html.twig');
    }
}
