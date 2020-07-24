<?php

namespace App\Entity;

use App\Repository\ClosingDaysRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClosingDaysRepository::class)
 */
class ClosingDays
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function forceYear($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $newStartDate = new DateTime();
        $newStartDate->setDate($year, date_format($this->startDate, "m"), date_format($this->startDate, "d"))
            ->setTime(0, 0, 0);
        $this->startDate = $newStartDate;

        $newEndDate = new DateTime();
        $newEndDate->setDate($year, date_format($this->endDate, "m"),  date_format($this->endDate, "d"))
            ->setTime(0, 0, 0);
        $this->endDate = $newEndDate;


        return $this;
    }
}
