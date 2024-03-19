<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable;

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
                $columns[] = (new Column())
                    ->setType($column->getType()->getName())
                    ->setName($column->getName())
                    ->setPrimaryKey($primaryKeyName === $column->getName())
                    ->setComment($column->getComment());
            });
            return (new Table())
                ->setName($table->getName())
                ->setComment($table->getComment())
                ->setDDL($this->finder->getDDL($table->getName()))
                ->setColumns($columns);
        });
        $this->info(PHP_EOL);
        $this->info('Generate Table.');
        $this->generator
            ->setFileName($this->getOutputFileName())
            ->showDDL(false)
            ->generate($tables)
            ->export();
        $this->info(PHP_EOL);
        $this->info('Export to ' . $this->getOutputFileName());
    }

    protected function getOutputFileName(): string
    {
        return $this->argument('filename') ?: $this->finder->getDatabasesName() . '_' . date('YmdHis') . '.docx';
    }
}
