<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
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

    public function getTaskByUser(User $objUser)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id,t.title,t.taskDescription,t.dueDate,ts.name as taskStatus,tc.name as taskCategory')
            ->join('t.status','ts')
            ->join('t.category','tc')
            ->where('t.user= :user')
            ->setParameter('user', $objUser)
            ->orderBy('t.id', 'desc')->getQuery()->getResult();
    }
}
