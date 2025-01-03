<?php

namespace App\Repository;

use App\Entity\RateLimit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RateLimit>
 * Repository class for managing rate-limiting functionality.
 */
class RateLimitRepository extends ServiceEntityRepository
{
    private int $gptRateLimit;

    /**
     * Constructor to initialize the repository and GPT rate limit.
     *
     * @param ManagerRegistry $registry
     * @param int $gptRateLimit The maximum number of requests allowed per hour.
     */
    public function __construct(ManagerRegistry $registry, $gptRateLimit)
    {
        parent::__construct($registry, RateLimit::class);
        $this->gptRateLimit = $gptRateLimit;
    }

    /**
     * Checks if the GPT rate limit has been exceeded.
     *
     * Deletes records older than one hour and compares the current request count to the limit.
     * If exceeded, returns the time when the next request will be allowed.
     * Otherwise, persists the current request and returns false.
     *
     * @return bool|\DateTimeImmutable Returns false if within limit or the next available time if exceeded.
     */
    public function isExceeded()
    {
        $anHourAgo = new \DateTimeImmutable('-1 hour');

        // Remove outdated records older than one hour.
        $this->createQueryBuilder('r')
            ->delete()
            ->where('r.requested_at < :anHourAgo')
            ->setParameter('anHourAgo', $anHourAgo)
            ->getQuery()
            ->execute();

        // Count the current number of requests.
        $count = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Fetch the oldest request timestamp.
        $oldest = $this->createQueryBuilder('r')
            ->select('r.requested_at')
            ->orderBy('r.requested_at', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $rateLimit = new RateLimit();

        // Check if the rate limit is exceeded.
        if ($count > $this->gptRateLimit) {
            if ($oldest) {
                // Return the time when the next request is allowed.
                return $oldest['requested_at']->add(new \DateInterval('PT1H'));
            } else {
                return true;
            }
        } else {
            // Persist the current request if within the limit.
            $rateLimit = new RateLimit();
            $em = $this->getEntityManager();
            $em->persist($rateLimit);
            $em->flush();
            return false;
        }
    }

    //    /**
    //     * @return RateLimit[] Returns an array of RateLimit objects
    //     * Example method for querying RateLimits by a specific field.
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

    //    /**
    //     * Example method for finding a single RateLimit by a specific field.
    //     */
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