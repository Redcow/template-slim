<?php

namespace Infrastructure\Database;

use Infrastructure\Database\interfaces\DatabaseInterface;

abstract class DatabaseFactory implements DatabaseInterface
{
    final protected function __construct()
    {}

    abstract static public function getDb(): DatabaseInterface;
}