<?php

namespace App\Repository;

use App\Entity\Attendance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attendance>
 */
class AttendanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendance::class);
    }

   public function findattendance($employee)
   {
      $todayStart =  (new \DateTime())->format('Y-m-d');
    return $this->createQueryBuilder('a')
                ->andWhere('a.employee =:employee')
                ->andWhere('a.date = :date')
                ->setParameter('employee',$employee)
                ->setParameter('date',$todayStart)
                ->getQuery()
                ->getOneOrNullResult();
   }
}
