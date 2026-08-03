<?php


namespace App\EventListener;


use App\Event\AttendanceCheckedOutEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: AttendanceCheckedOutEvent::class)]
class AttendanceCheckedOutListener
{
   public function __construct(
    private readonly EntityManagerInterface $entityManager
   ){}
  public function __invoke(AttendanceCheckedOutEvent $event): void
  {
     
    $attendance = $event->getAttendance();
    $checkInSt  = $attendance->getCheckIn();
    $checkOutSt = $attendance->getCheckOut();
    if($checkInSt && $checkOutSt){
        $inTime = new \DateTime($checkInSt);
        $outTime = new \DateTime($checkOutSt);
        $interval = $inTime->diff($outTime);
       $formattedHours = sprintf('%d hrs %d mins',
       $interval->h, $interval->i);

       $attendance->setWorkingHours($formattedHours);
       $this->entityManager->flush();
    }
  }
}