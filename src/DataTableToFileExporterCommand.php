<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable;

use Doctrine\DBAL\Schema\Identifier as DoctrineIdentifier;
use Doctrine\DBAL\Schema\Index as DoctrineIndex;
use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Exception;
use Illuminate\Console\Command;
use Polynds\ExportTable\Generator\Generator;
use Polynds\ExportTable\Generator\WordGenerator;

class DataTableToFileExporterCommand extends Command
{
    protected $signature = 'export:table {filename?}';

    protected $description = 'Export table.';

    protected Generator $generator;

    protected Finder $finder;

    public function __construct()
    {
        parent::__construct();
        $this->generator = new WordGenerator();
        $this->finder = new Finder();
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $this->info("Find tables...");
        $helper = $this->getHelper('process');
        $helper->run($this->output, array('figlet', 'Symfony'));

        $tables = $this->finder->getAllTables();

        $this->info("Found {$tables->count()} tables.");
        $ignore = config('export_table.ignore', []);
        $ignore && $this->info('Ignore tables:' . implode(', ', $ignore));
        $this->info('Parsing Table Structure.');

        $bar = $this->output->createProgressBar($tables->count());
        $tables->transform(function (DoctrineTable $table) use ($bar) {
            $bar->advance();
            $primaryKeyName = optional(optional($table->getPrimaryKey())->getColumns())[0] ?? null;
            $columns = [];
            collect($table->getColumns())->map(function (DoctrineColumn $column) use ($primaryKeyName, &$columns) {
                $length = strval($column->getLength() ?? $column->getPrecision());
                if ($column->getType()->getName() === 'decimal') {
                    $length = $column->getPrecision() . ',' . $column->getScale();
                }

                $columns[] = (new Column())
                    ->setType($column->getType()->getName())
                    ->setName($column->getName())
                    ->setPrimaryKey($primaryKeyName === $column->getName())
                    ->setTypeLength($length)
                    ->setComment($column->getComment());
            });
            $indexs = [];
            collect($table->getIndexes())->map(function (DoctrineIndex $index) use (&$indexs) {
                $indexs[] = (new Indexer())
                    ->setName($index->getName())
                    ->setColumns($index->getColumns())
                    ->setIsPrimary($index->isPrimary())
                    ->setIsUnique($index->isUnique());
            });
            return (new Table())
                ->setName($table->getName())
                ->setComment($table->getComment())
                ->setDDL($this->finder->getDDL($table->getName()))
                ->setIndexers($indexs)
                ->setColumns($columns);
        });
        $this->newLine();
        $this->info('Generate Table.');
        $this->generator
            ->setFileName($this->getOutputFileName())
            ->showDDL(true)
            ->generate($tables)
            ->export();
        $this->info('Export to ' . $this->getOutputFileName());
    }

    protected function getOutputFileName(): string
    {
        return $this->argument('filename') ?: $this->finder->getDatabasesName() . '_' . date('YmdHis') . '.docx';
    }
}
