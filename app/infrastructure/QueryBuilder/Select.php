<?php

namespace Infrastructure\QueryBuilder;

class Select extends QueryState
{

    public function getQuery(): string
    {
        $qb = $this->queryBuilder;
        $select = "SELECT ".join(', ', $qb->getFields())." ";
        $from = "FROM ".$qb->getFrom()." ";
        $join = !$qb->getJoins()->isEmpty() ? join(' ', $qb->getJoins()->map(function(Join $join) {
                                                            return "INNER JOIN ".$join;
                                                       })).' ' : "";
        $leftJoin = !$qb->getLeftJoins()->isEmpty()  ? join(' ', $qb->getLeftJoins()->map(function(Join $join) {
                                                                    return "LEFT JOIN ".$join;
                                                               })).' ' : "";
        $where = $qb->getWhere()->getText() ? "WHERE ".$qb->getWhere()." " : "";
        $groupBy = $qb->getGroupBy() ? "GROUP BY ".$qb->getGroupBy()." " : "";
        $having = ( $qb->getHaving() && !empty($groupBy)) ? "HAVING ". $qb->getHaving() : "";

        return $select
              .$from
              .$join
              .$leftJoin
              .$where
              .$groupBy
              .$having;
    }
}