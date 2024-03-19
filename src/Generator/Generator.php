<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable\Generator;

use Illuminate\Support\Collection;

interface Generator
{
    public function generate(Collection $table): self;

    public function export(): void;
}
