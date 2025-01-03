<?php

namespace App\Util;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Uid\Uuid;

class DatabaseUtil
{
    /**
     * Filters questions by excluding specific IDs from the query results.
     *
     * @param QueryBuilder $qb The Doctrine QueryBuilder instance.
     * @param Uuid[] $filterIds Array of UUIDs to exclude from the results.
     * @param string $alias The alias for the entity being filtered, default is 'q'.
     * @return QueryBuilder The modified QueryBuilder with exclusion filters applied.
     */
    public static function filterQuestionsById(QueryBuilder $qb, array $filterIds, $alias = 'q')
    {
        foreach ($filterIds as $index => $id) {
            $qb->andWhere($qb->expr()->neq($alias . '.id', ':exc' . $index))
                ->setParameter('exc' . $index, $id->toBinary());
        }
        return $qb;
    }
}
