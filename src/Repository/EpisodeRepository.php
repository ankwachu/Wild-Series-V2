<?php

namespace App\Repository;

use DateTime;
use App\Entity\Episode;
use App\Entity\Program;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Episode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Episode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Episode[]    findAll()
 * @method Episode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }

    const NUMBER_DATES = 12;

    public function findByDateExpiration()
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.beginAt >= :dateToday')
            ->setParameter('dateToday', new DateTime('NOW'))
            ->orderBy('e.beginAt', 'ASC')
            ->setMaxResults(self::NUMBER_DATES);
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findByDate()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT e.title, e.slug, e.beginAt, p.name, p.poster FROM App\Entity\Episode e JOIN App\Entity\Program p where e.seasons  = p.id');

        return $query->execute();
    }
}
