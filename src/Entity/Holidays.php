<?php

namespace App\Entity;

use App\Repository\HolidaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;


#[ORM\Entity(repositoryClass: HolidaysRepository::class)]
#[ApiResource]
class Holidays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\OneToMany(mappedBy: 'holidays', targetEntity: Personal::class)]
    private Collection $personal;

    #[ORM\ManyToMany(targetEntity: Signin::class, mappedBy: 'holidays')]
    private Collection $workshops;

    public function __construct()
    {
        $this->personal = new ArrayCollection();
        $this->workshops = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Personal>
     */
    public function getPersonal(): Collection
    {
        return $this->personal;
    }

    public function addPersonal(Personal $personal): self
    {
        if (!$this->personal->contains($personal)) {
            $this->personal->add($personal);
            $personal->setHolidays($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personal->removeElement($personal)) {
            // set the owning side to null (unless already changed)
            if ($personal->getHolidays() === $this) {
                $personal->setHolidays(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Signin>
     */
    public function getWorkshops(): Collection
    {
        return $this->workshops;
    }

    public function addWorkshop(Signin $workshop): self
    {
        if (!$this->workshops->contains($workshop)) {
            $this->workshops->add($workshop);
            $workshop->addHoliday($this);
        }

        return $this;
    }

    public function removeWorkshop(Signin $workshop): self
    {
        if ($this->workshops->removeElement($workshop)) {
            $workshop->removeHoliday($this);
        }

        return $this;
    }
}
