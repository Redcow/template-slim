<?php

namespace Infrastructure\QueryBuilder;

use Closure;

class QueryBuilder {
    private QueryState $command;
    private array $fields;
    private Table $_table;
    private Table $_from;
    private JoinCollection $_join;
    private JoinCollection $_left_join;
    private Where $_where;
    private ?string $_groupBy;
    private ?string $_having;
    private array $_set;
    /** @var array<string, string[]> */
    private array $_values;

    public function __construct()
    {
        $this->reset();
    }
    public function select(string ...$fields): self
    {
        $this->command = new Select($this);
        $this->fields = $fields;
        return $this;
    }
    public function addSelect(string ...$fields): self
    {
        $this->fields = array_merge(
            $this->fields,
            $fields
        );
        return $this;
    }
    public function delete(): self
    {
        $this->command = new Delete($this);
        return $this;
    }
    public function update(string $table, ?string $alias = null): self
    {
        $this->command = new Update($this);
        $this->_table->name = $table;
        if ($alias) {
            $this->_table->alias = $alias;
        }
        return $this;
    }
    public function insertInto(string $table, string ...$columns): Closure
    {
        $this->command = new InsertInto($this);
        $this->_table->name = $table;
        $this->fields = $columns;
        return $this->values(...);
    }
    public function from(string $table, ?string $alias = null): self
    {
        $this->_from->name = $table;
        if ($alias) {
            $this->_from->alias = $alias;
        }
        return $this;
    }
    public function innerJoin($table, string $onClause): self
    {
        $join = new Join();

        if(is_array($table)) {
            $join->name = $table[0];
            $join->alias = $table[1];
        } else {
            $join->name = $table;
        }

        $join->on = $onClause;

        $this->_join->add($join);

        return $this;
    }
    public function leftJoin(array|string $table, string $onClause): self
    {
        $join = new Join();

        if(is_array($table)) {
            $join->name = $table[0];
            $join->alias = $table[1];
        } else {
            $join->name = $table;
        }

        $join->on = $onClause;

        $this->_left_join->add($join);

        return $this;
    }
    public function set(string $columnName, $value): self
    {
        $this->_set[] = "$columnName = $value";
        return $this;
    }

    public function values(string ...$values): Closure
    {
        //$this->_values = array_merge($values, $this->_values);
        $this->_values[] = $values;
        return $this->values(...);
    }

    public function where(Where|string $condition): self
    {
        $this->_where->and($condition);
        return $this;
    }

    public function andWhere(Where|string $condition): self
    {
        $this->_where->and($condition);
        return $this;
    }

    public function orWhere(Where|string $condition): self
    {
        $this->_where->or($condition);
        return $this;
    }
    public function groupBy(string $groupBy): self
    {
        $this->_groupBy = $groupBy;
        return $this;
    }
    public function having(string $condition): self
    {
        $this->_having = $condition;
        return $this;
    }
    public function getQuery(): string
    {
        return $this->command->getQuery();
    }
    public function getTable(): Table
    {
        return $this->_table;
    }
    public function getFields(): array
    {
        return $this->fields;
    }
    public function getFrom(): Table
    {
        return $this->_from;
    }
    public function getJoins(): JoinCollection
    {
        return $this->_join;
    }
    public function getLeftJoins(): JoinCollection
    {
        return $this->_left_join;
    }
    public function getWhere(): Where
    {
        return $this->_where;
    }
    public function getGroupBy(): ?string
    {
        return $this->_groupBy;
    }
    public function getHaving(): ?string
    {
        return $this->_having;
    }
    public function getSet(): array
    {
        return $this->_set;
    }
    public function getValues(): array
    {
        return $this->_values;
    }
    public function reset(): void
    {
        $this->command = new Select($this);
        $this->_table = new Table();
        $this->fields = [];
        $this->_from = new Table();
        $this->_join = new JoinCollection();
        $this->_left_join = new JoinCollection();
        $this->_where = new Where();
        $this->_groupBy = null;
        $this->_having = null;
        $this->_set = [];
        $this->_values = [];
    }
}