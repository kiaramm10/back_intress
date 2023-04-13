<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AccumulatedVacationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccumulatedVacationRepository::class)]
#[ApiResource]
class AccumulatedVacation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id = null;

    #[ORM\ManyToOne(inversedBy: 'accumulatedVacations')]
    private ?Holidays $days = null;

    #[ORM\ManyToOne(inversedBy: 'accumulatedVacations')]
    private ?Personal $personal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDays(): ?Holidays
    {
        return $this->days;
    }

    public function setDays(?Holidays $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getEmployee(): ?Personal
    {
        return $this->employee;
    }

    public function setEmployee(?Personal $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getTotalVacationDays(): int
    {
        return $this->totalVacationDays;
    }

    public function setTotalVacationDays(int $totalVacationDays): self
    {
        $this->totalVacationDays = $totalVacationDays;

        return $this;
    }

    public function getUsedVacationDays(): int
    {
        return $this->usedVacationDays;
    }

    public function setUsedVacationDays(int $usedVacationDays): self
    {
        $this->usedVacationDays = $usedVacationDays;

        return $this;
    }

    public function addVacationDays(int $days): self
    {
        $this->totalVacationDays += $days;

        return $this;
    }

    public function useVacationDays(int $days): self
    {
        if ($this->totalVacationDays - $this->usedVacationDays >= $days) {
            $this->usedVacationDays += $days;
        } else {
            throw new \Exception('Not enough vacation days');
        }

        return $this;
    }
}
