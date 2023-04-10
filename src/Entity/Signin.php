<?php

namespace App\Entity;

use App\Repository\SigninRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;


#[ORM\Entity(repositoryClass: SigninRepository::class)]
#[ApiResource]
class Signin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $timestart = null;

    #[ORM\Column(length: 255)]
    private ?string $timestop = null;

    #[ORM\Column(length: 255)]
    private ?string $timefinish = null;

    #[ORM\Column(length: 255)]
    private ?string $hourcount = null;

    #[ORM\OneToMany(mappedBy: 'signin', targetEntity: Personal::class)]
    private Collection $personal;

    #[ORM\ManyToMany(targetEntity: Holidays::class, inversedBy: 'workshops')]
    private Collection $holidays;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'signin')]
    private Collection $workshops;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'workshops')]
    private Collection $signin;

    public function __construct()
    {
        $this->personal = new ArrayCollection();
        $this->holidays = new ArrayCollection();
        $this->workshops = new ArrayCollection();
        $this->signin = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestart(): ?string
    {
        return $this->timestart;
    }

    public function setTimestart(string $timestart): self
    {
        $this->timestart = $timestart;

        return $this;
    }

    public function getTimestop(): ?string
    {
        return $this->timestop;
    }

    public function setTimestop(string $timestop): self
    {
        $this->timestop = $timestop;

        return $this;
    }

    public function getTimefinish(): ?string
    {
        return $this->timefinish;
    }

    public function setTimefinish(string $timefinish): self
    {
        $this->timefinish = $timefinish;

        return $this;
    }

    public function getHourcount(): ?string
    {
        return $this->hourcount;
    }

    public function setHourcount(string $hourcount): self
    {
        $this->hourcount = $hourcount;

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
            $personal->setSignin($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personal->removeElement($personal)) {
            // set the owning side to null (unless already changed)
            if ($personal->getSignin() === $this) {
                $personal->setSignin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Holidays>
     */
    public function getHolidays(): Collection
    {
        return $this->holidays;
    }

    public function addHoliday(Holidays $holiday): self
    {
        if (!$this->holidays->contains($holiday)) {
            $this->holidays->add($holiday);
        }

        return $this;
    }

    public function removeHoliday(Holidays $holiday): self
    {
        $this->holidays->removeElement($holiday);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getWorkshops(): Collection
    {
        return $this->workshops;
    }

    public function addWorkshop(self $workshop): self
    {
        if (!$this->workshops->contains($workshop)) {
            $this->workshops->add($workshop);
        }

        return $this;
    }

    public function removeWorkshop(self $workshop): self
    {
        $this->workshops->removeElement($workshop);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSignin(): Collection
    {
        return $this->signin;
    }

    public function addSignin(self $signin): self
    {
        if (!$this->signin->contains($signin)) {
            $this->signin->add($signin);
            $signin->addWorkshop($this);
        }

        return $this;
    }

    public function removeSignin(self $signin): self
    {
        if ($this->signin->removeElement($signin)) {
            $signin->removeWorkshop($this);
        }

        return $this;
    }
}
