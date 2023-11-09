<?php

namespace Infrastructure\QueryBuilder;

abstract class QueryState
{
    public function __construct(
        protected QueryBuilder $queryBuilder
    ) {}

    public abstract function getQuery(): string;
}