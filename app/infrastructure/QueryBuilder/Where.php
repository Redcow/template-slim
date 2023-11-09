<?php

namespace Infrastructure\QueryBuilder;

use Exception;

class Where {
    private array $_and;
    private array $_or;

    public function __construct()
    {
        $this->_and = [/*"1 = 1"*/];
        $this->_or = [];
    }

    public function and($condition): self
    {
        $this->checkCondition($condition);
        $this->_and[] = $condition;
        return $this;
    }

    public function or($condition): self
    {
        $this->checkCondition($condition);
        $this->_or[] = $condition;
        return $this;
    }

    public function getText(): string
    {
        $andConditions = array_map(function($condition) {
            if(is_string($condition)) {
                return $condition;
            }
            return '('.$condition->getText().')';
        }, $this->_and);

        $orConditions = array_map(function($condition) {
            if(is_string($condition)) {
                return $condition;
            }
            return '('.$condition->getText().')';
        }, $this->_or);

        return join(' AND ', $andConditions)
            .join(' OR ', $orConditions);
    }

    public function __toString() {
        return $this->getText();
    }

    /**
     * @throws Exception
     */
    private function checkCondition($condition): void
    {
        if (!is_string($condition) && !($condition instanceof Where)) {
            throw new Exception("invalid where condition, must be string or subWhere");
        }
    }
}