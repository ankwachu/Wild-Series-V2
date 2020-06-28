<?php

namespace App\Repository;

use App\Entity\Program;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function findAll()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }

    public function search($name)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->execute();
    }

    /**
     * @return Program[]
     */
    public function findProgramByCategory(?Category $category = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.category = :category')
            ->setParameter('category', $category);
        if ($category == empty([$qb])) {
            return $qb = $this->findAll();
        }
        $qb->orderBy('p.name', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
