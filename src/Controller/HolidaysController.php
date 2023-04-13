<?php

namespace App\Controller;

use App\Entity\Holidays;
use App\Form\HolidaysType;
use App\Repository\HolidaysRepository;
use App\Repository\AccumulatedVacationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/holidays')]
class HolidaysController extends AbstractController
{
    #[Route('/', name: 'app_holidays_index', methods: ['GET'])]
    public function index(HolidaysRepository $holidaysRepository): Response
    {
        return $this->render('holidays/index.html.twig', [
            'holidays' => $holidaysRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_holidays_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HolidaysRepository $holidaysRepository): Response
    {
        $holiday = new Holidays();
        $form = $this->createForm(HolidaysType::class, $holiday);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $holidaysRepository->save($holiday, true);

            return $this->redirectToRoute('app_holidays_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('holidays/new.html.twig', [
            'holiday' => $holiday,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_holidays_show', methods: ['GET'])]
    public function show(Holidays $holiday, AccumulatedVacationRepository $accumulatedVacationRepository): Response
    {
        $employee = $holiday->getEmployee();
        $accumulatedVacation = $accumulatedVacationRepository->findOneBy(['days' => $holiday, 'employee' => $employee]);

        return $this->render('holidays/show.html.twig', [
            'holiday' => $holiday,
            'employees' => $holiday->getEmployees(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_holidays_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Holidays $holiday, HolidaysRepository $holidaysRepository, AccumulatedVacationRepository $accumulatedVacationRepository): Response
    {
        $form = $this->createForm(HolidaysType::class, $holiday);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $holidaysRepository->save($holiday, true);

            // Update accumulated vacation days
            $employee = $holiday->getEmployee();
            $accumulatedVacation = $accumulatedVacationRepository->findOneBy(['days' => $holiday, 'employee' => $employee]);
            if (!$accumulatedVacation) {
                $accumulatedVacation = new AccumulatedVacation();
                $accumulatedVacation->setDays($holiday);
                $accumulatedVacation->setEmployee($employee);
            }
            $accumulatedVacation->useVacationDays($holiday->getDuration());
            $accumulatedVacationRepository->save($accumulatedVacation, true);

            return $this->redirectToRoute('app_holidays_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('holidays/edit.html.twig', [
            'holiday' => $holiday,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_holidays_delete', methods: ['POST'])]
    public function delete(Request $request, Holidays $holiday, HolidaysRepository $holidaysRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$holiday->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($holiday);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_holidays_index');
    }
}   
