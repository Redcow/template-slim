<?php

namespace Infrastructure\QueryBuilder;
class Delete extends QueryState
{

    public function getQuery(): string
    {
        $qb = $this->queryBuilder;
        $delete = "DELETE ";
        $from = "FROM ".$qb->getFrom()." ";
        $where = $qb->getWhere()->getText() ? "WHERE ".$qb->getWhere() : "";

        return $delete
              .$from
              .$where;
    }
}