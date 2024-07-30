<?php

namespace App\Repository;

use App\Entity\RateLimit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RateLimit>
 */
class RateLimitRepository extends ServiceEntityRepository
{

    private int $gptRateLimit;

    public function __construct(ManagerRegistry $registry, $gptRateLimit)
    {
        parent::__construct($registry, RateLimit::class);
        $this->gptRateLimit = $gptRateLimit;
    }


    public function isExceeded()
    {
        $anHourAgo = new \DateTimeImmutable('-1 hour');

        $this->createQueryBuilder('r')
            ->delete()
            ->where('r.requested_at < :anHourAgo')
            ->setParameter('anHourAgo', $anHourAgo)
            ->getQuery()
            ->execute();

        $count = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $oldest = $this->createQueryBuilder('r')
            ->select('r.requested_at')
            ->orderBy('r.requested_at', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $rateLimit = new RateLimit();

        if ($count > $this->gptRateLimit) {
            if ($oldest) {
                return $oldest['requested_at']->add(new \DateInterval('PT1H'));
            } else {
                return true;
            }
        } else {
            $em = $this->getEntityManager();
            $em->persist($rateLimit);
            $em->flush();
            return false;
        }
    }

    //    /**
    //     * @return RateLimit[] Returns an array of RateLimit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RateLimit
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
