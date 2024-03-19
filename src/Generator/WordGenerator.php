<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable\Generator;

use Illuminate\Support\Collection;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Polynds\ExportTable\Column;
use Polynds\ExportTable\Table;

class WordGenerator implements Generator
{
    protected PhpWord $phpWord;

    protected string $fileName;

    protected bool $ddl;

    public function __construct(string $fileName = 'WordGenerator.docx', bool $ddl = false)
    {
        $this->fileName = $fileName;
        $this->ddl = $ddl;
        $this->phpWord = new PhpWord();
        $this->phpWord->setDefaultFontName('Times New Roman');
        $this->phpWord->setDefaultFontSize(12);
    }

    public function generate(Collection $tables): self
    {
        $colorStyle = ['bgColor' => 'F5F5F5'];
        $valignStyle = ['valign' => 'center'];
        $cellStyle = $valignStyle + $colorStyle;
        $subCellStyle = $valignStyle;
        $tables->each(function (Table $table) use ($subCellStyle, $cellStyle, $colorStyle) {
            $section = $this->phpWord->addSection();
            $section->getStyle()->setBreakType('nextColumn');

            $this->phpWord->addTableStyle('myTable', ['borderColor' => '000000', 'borderSize' => 6, 'cellMargin' => 120], ['bgColor' => '000000']);
            $myTable = $section->addTable('myTable');
            $myTable->addRow();
            $myTable->addCell(8000, ['gridSpan' => 4] + $colorStyle)->addText($table->getName() . PHP_EOL . $table->getComment());
            $myTable->addRow(null, $colorStyle);
            $fontStyle = ['bold' => true, 'align' => 'center'];
            $myTable->addCell(2000, $cellStyle)->addText('字段', $fontStyle);
            $myTable->addCell(2000, $cellStyle)->addText('数据类型', $fontStyle);
            $myTable->addCell(2000, $cellStyle)->addText('主键', $fontStyle);
            $myTable->addCell(2000, $cellStyle)->addText('备注', $fontStyle);
            /** @var Column $column */
            foreach ($table->getColumns() as $column) {
                $myTable->addRow();
                $myTable->addCell(2000, $subCellStyle)->addText($column->getName());
                $myTable->addCell(2000, $subCellStyle)->addText($column->getType());
                $myTable->addCell(2000, $subCellStyle)->addText($column->getPrimaryKeyText());
                $myTable->addCell(2000, $subCellStyle)->addText($column->getComment());
            }
            if ($this->ddl) {
                $myTable->addRow();
                $myTable->addCell(8000, ['gridSpan' => 4, 'wardWrap' => 'true'])->addText($table->getDDL());
            }
        });

        return $this;
    }

    public function export(): void
    {
        $writer = IOFactory::createWriter($this->phpWord);
        $writer->save(base_path() . '/' . $this->fileName);
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function showDDL(bool $ddl = true): self
    {
        $this->ddl = $ddl;
        return $this;
    }
}
