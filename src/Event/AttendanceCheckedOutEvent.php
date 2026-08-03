<?php

namespace App\Event;

use App\Entity\Attendance;
use Symfony\Contracts\EventDispatcher\Event;



class AttendanceCheckedOutEvent extends Event
{

    public function __construct(private readonly Attendance $attendance)
    {
    }

    public function getAttendance(): Attendance
    {
        return $this->attendance;
    }
}
