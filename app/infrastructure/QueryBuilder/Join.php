<?php

namespace Infrastructure\QueryBuilder;

class Join extends Table {
    public string $on;

    public function getText(): string
    {
        return parent::getText()." ON ".$this->on;
    }

    public function __toString(): string
    {
        return $this->getText();
    }
}