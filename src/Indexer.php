<?php

namespace Polynds\ExportTable;

class Indexer
{

    private ?string $name;

    /**
     * @var string[]|null
     */
    private ?array $columns;

    private ?bool $isUnique;

    private ?bool $isPrimary;

    public function __construct()
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Indexer
    {
        $this->name = $name;
        return $this;
    }

    public function getColumns(): ?array
    {
        return $this->columns;
    }

    public function getColumnsText(): ?string
    {
        return $this->columns ? implode(', ', $this->columns) : null;
    }

    public function setColumns(?array $columns): Indexer
    {
        $this->columns = $columns;
        return $this;
    }

    public function getIsUnique(): ?bool
    {
        return $this->isUnique;
    }

    public function getIsUniqueText(): ?string
    {
        return $this->isUnique ? 'UN' : null;
    }

    public function setIsUnique(?bool $isUnique): Indexer
    {
        $this->isUnique = $isUnique;
        return $this;
    }


    public function getIsPrimary(): ?bool
    {
        return $this->isPrimary;
    }

    public function getIsPrimaryText(): ?string
    {
        return $this->isPrimary ? 'PK' : null;
    }

    public function setIsPrimary(?bool $isPrimary): Indexer
    {
        $this->isPrimary = $isPrimary;
        return $this;
    }
}
