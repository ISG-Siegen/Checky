<?php

namespace App\Util;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Uid\Uuid;

class DatabaseUtil
{

    /**
     * @param Uuid[] $except
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
