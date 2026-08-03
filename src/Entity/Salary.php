<?php

namespace App\Entity;

use App\Repository\SalaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalaryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Salary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'salaries')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Employee $employee = null;

    #[ORM\Column(length: 255)]
    private ?string $month = null;

    #[ORM\Column(length: 255)]
    private ?string $year = null;

    #[ORM\Column(length: 255)]
    private ?string $basicSalary = null;

    #[ORM\Column(length: 255)]
    private ?string $bonus = null;

    #[ORM\Column(length: 255)]
    private ?string $deduction = null;

    #[ORM\Column(length: 255)]
    private ?string $netSalary = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentStatus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(?string $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getBasicSalary(): ?string
    {
        return $this->basicSalary;
    }

    public function setBasicSalary(?string $basicSalary): static
    {
        $this->basicSalary = $basicSalary;

        return $this;
    }

    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    public function setBonus(?string $bonus): static
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getDeduction(): ?string
    {
        return $this->deduction;
    }

    public function setDeduction(?string $deduction): static
    {
        $this->deduction = $deduction;

        return $this;
    }

    public function getNetSalary(): ?string
    {
        return $this->netSalary;
    }

    public function setNetSalary(?string $netSalary): static
    {
        $this->netSalary = $netSalary;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(?string $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }
}
