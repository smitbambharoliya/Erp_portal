<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }


    public function serchTeamMember($departmentId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.department = :departmentId')
            ->andWhere('e.status = :status')
            ->setParameter('departmentId', $departmentId)
            ->setParameter('status', 'Active');
          
    }

}
