<?php

namespace Infrastructure\QueryBuilder;

class InsertInto extends QueryState
{

    public function getQuery(): string
    {
        $qb = $this->queryBuilder;

        $insert  = "INSERT INTO ".$qb->getTable()." ";
        $columns = "(".join(", ", $qb->getFields()).") ";

        $values  = "VALUES ". join(", ", array_reduce(
            $qb->getValues(),
            fn (array $result, array $rowValues) => [...$result, "(".join(", ", $rowValues).")"],
            []
        ));

        return $insert
              .$columns
              .$values;
    }
}