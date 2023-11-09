<?php

namespace Infrastructure\QueryBuilder;

class Update extends QueryState
{
    public function getQuery(): string
    {
        $qb = $this->queryBuilder;
        $update = "UPDATE ".$qb->getTable()." ";
        $set = count($qb->getSet()) > 0 ? "SET ".join(", ", $qb->getSet())." " : "";
        $where = "WHERE ".$qb->getWhere();

        return $update
              .$set
              .$where;
    }
}