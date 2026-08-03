<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @return Task[]
     */
    public function findByEmployee(?Employee $employee): array
    {
        if (!$employee) {
            return [];
        }

        return $this->createQueryBuilder('t')
            ->innerJoin('t.employee', 'e')
            ->andWhere('e = :employee')
            ->setParameter('employee', $employee)
            ->getQuery()
            ->getResult();
    }
}
