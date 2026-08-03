<?php


namespace App\Service;


use App\Entity\LeaveRequest;
use Doctrine\ORM\EntityManagerInterface;

class LeaveRequestService
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function approve(LeaveRequest $leaveRequest): void
    {
        $leaveRequest->setStatus("Approved");
        $this->entityManager->flush();
    }
    public function reject(LeaveRequest $leaveRequest): void
    {
        $leaveRequest->setStatus("Rejected");
        $this->entityManager->flush();
    }
}
