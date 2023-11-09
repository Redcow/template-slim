<?php

namespace Infrastructure\QueryBuilder;

class Table {

    public string $name;

    public ?string $alias = null;

    public function getText(): string
    {
        return $this->name . ($this->alias ? ' '.$this->alias : "");
    }

    public function __toString(): string
    {
        return $this->getText();
    }
}