<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable;

class Column
{
    protected ?string $name;

    protected ?string $type;

    protected ?string $comment;

    protected ?bool $primaryKey;

    public function __construct() {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Column
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Column
    {
        $this->type = $type;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): Column
    {
        $this->comment = $comment;
        return $this;
    }

    public function getPrimaryKey(): ?bool
    {
        return $this->primaryKey;
    }

    public function getPrimaryKeyText(): ?string
    {
        return $this->primaryKey ? 'PK' : null;
    }

    public function setPrimaryKey(?bool $primaryKey): Column
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }
}
