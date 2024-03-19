<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable;

class Table
{
    protected ?string $name;

    protected ?string $comment;

    protected ?array $columns;

    protected ?string $primaryKeyName;

    protected ?string $ddl;

    public function __construct() {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Table
    {
        $this->name = $name;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): Table
    {
        $this->comment = $comment;
        return $this;
    }

    public function getColumns(): ?array
    {
        return $this->columns;
    }

    public function setColumns(?array $columns): Table
    {
        $this->columns = $columns;
        return $this;
    }

    public function getPrimaryKeyName(): ?string
    {
        return $this->primaryKeyName;
    }

    public function setPrimaryKeyName(?string $primaryKeyName): Table
    {
        $this->primaryKeyName = $primaryKeyName;
        return $this;
    }

    public function getDDL(): ?string
    {
        return $this->ddl;
    }

    public function setDDL(?string $ddl): Table
    {
        $this->ddl = $ddl;
        return $this;
    }
}
