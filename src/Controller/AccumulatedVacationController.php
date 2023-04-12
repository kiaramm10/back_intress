<?php

namespace App\Controller;

use App\Entity\Personal;
use App\Repository\AccumulatedVacationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccumulatedVacationController extends AbstractController
{
    #[Route('/accumulated/vacation/{id}', name: 'app_accumulated_vacation')]
    public function index(Personal $employee, AccumulatedVacationRepository $accumulatedVacationRepository): Response
    {
        $accumulatedVacations = $accumulatedVacationRepository->findBy(['employee' => $employee]);

        $totalVacationDays = 0;
        $usedVacationDays = 0;
        foreach ($accumulatedVacations as $accumulatedVacation) {
            $totalVacationDays += $accumulatedVacation->getDays();
            $usedVacationDays += $accumulatedVacation->getDaysUsed();
        }
        $remainingVacationDays = $totalVacationDays - $usedVacationDays;

        return $this->render('accumulated_vacation/index.html.twig', [
            'employee' => $employee,
            'accumulated_vacations' => $accumulatedVacations,
            'total_vacation_days' => $totalVacationDays,
            'remaining_vacation_days' => $remainingVacationDays,
            'used_vacation_days' => $usedVacationDays,
        ]);
    }
}
