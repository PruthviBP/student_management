<?php

namespace App\Repository;

use App\Entity\Students;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Students>
 *
 * @method Students|null find($id, $lockMode = null, $lockVersion = null)
 * @method Students|null findOneBy(array $criteria, array $orderBy = null)
 * @method Students[]    findAll()
 * @method Students[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Students::class);
    }

}
