<?php

namespace Test\sql;

use Infrastructure\Database\MysqlDatabase;

class TestDatabase extends MysqlDatabase
{
    /**
     * Régénère l'ensemble de la base de données de test
     */
    public function resetDatabase(): void
    {
        $this->rewriteDatabase();
        $this->rewriteSchemas();
        $this->rewriteData();
    }

    /**
     * Réinitialise les données en base
     */
    public function resetData(): void
    {
        $this->rewriteData();
    }

    private function rewriteDatabase(): void
    {
        $sql = file_get_contents(__DIR__.'/1_database.test.sql');
        static::$pdo->exec($sql);
    }

    private function rewriteSchemas(): void
    {
        $sql = file_get_contents(__DIR__.'/2_schemas.test.sql');
        static::$pdo->exec($sql);
    }

    private function rewriteData(): void
    {
        $sql = file_get_contents(__DIR__.'/3_data.test.sql');
        static::$pdo->exec($sql);
    }
}