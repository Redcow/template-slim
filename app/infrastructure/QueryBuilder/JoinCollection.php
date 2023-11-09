<?php

namespace Infrastructure\QueryBuilder;

use Closure;
use Iterator;

class JoinCollection implements Iterator {
    /** @var Join[] */
    public array $joins;

    private int $index = 0;

    public function __construct(array $collection = []) {
        $this->joins = $collection;
    }

    public function add(Join $join): self
    {
        $exists = array_filter($this->joins, function(Join $_join) use ($join) {
            return $_join->name === $join->name && $_join->on === $join->on;
        });

        if(count($exists) === 0) {
            $this->joins[] = $join;
        }
        return $this;
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function current(): mixed
    {
        return $this->joins[$this->index];
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->joins[$this->index]);
    }

    public function map(Closure $callback): array
    {
        $mutatedList = [];
        foreach ($this->joins as $join) {
            $mutatedList[] = $callback($join);
        }
        return $mutatedList;
    }

    public function isEmpty(): bool
    {
        return empty($this->joins);
    }
}