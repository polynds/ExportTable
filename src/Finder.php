<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable;

use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Finder
{
    public function getAllTables(): Collection
    {
        $connection = $this->getConnection();

        $schema = $connection->getDoctrineSchemaManager();

        return collect($schema->listTables())->filter(function (DoctrineTable $table) {
            return !in_array($table->getName(), config('export_table.ignore', []), true)
                || in_array($table->getName(), config('export_table.whitelist', []), true);
        });
    }

    public function getConnection(): Connection
    {
        $connectionName = config('export_table.connection_name');

        return DB::connection($connectionName);
    }

    public function getDDL(string $tableName): ?string
    {
        $connection = $this->getConnection();

        $ddl = $connection->selectOne("SHOW CREATE TABLE {$tableName};");

        if ($ddl === false) {
            return null;
        }

        return str_replace("\n", '<w:br />', data_get($ddl, 'Create Table')) . ';';
    }

    public function getDatabasesName(): string
    {
        $connection = $this->getConnection();
        return $connection->getDatabaseName();
    }
}
