<?php
/**
 * planning/excel_reader2.php
 *
 * Compatibility layer for the old Spreadsheet_Excel_Reader 2.21 (2009, unmaintained), which this file
 * used to contain. process_tran_pps.php / process_tran_pps_latest.php read uploaded .xls files through
 * it, so the class name and the data layout they use are kept:
 *
 *   $data = new Spreadsheet_Excel_Reader($file);
 *   $data->sheets[$i]['cells'][$row][$col]   // 1-based; only non-empty cells exist
 *   $data->sheets[$i]['numRows'], ['numCols']
 *   $data->val($row, $col, $sheet), rowcount($sheet), colcount($sheet)
 *
 * The parsing itself is done by PhpSpreadsheet (composer.json), restricted to real Excel files.
 */

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once __DIR__ . '/../vendor/autoload.php';

class Spreadsheet_Excel_Reader
{
    /** @var array<int, array{cells: array<int, array<int, string>>, numRows: int, numCols: int}> */
    public array $sheets = [];
    public string $error = '';

    public function __construct(string $file = '', bool $store_extended_info = true, string $outputEncoding = '')
    {
        if ($file === '') {
            return;
        }
        try {
            $book = IOFactory::load($file, 0, [IOFactory::READER_XLS, IOFactory::READER_XLSX]);
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
            error_log('Spreadsheet_Excel_Reader: could not read ' . basename($file) . ': ' . $e->getMessage());
            return;
        }
        foreach ($book->getAllSheets() as $i => $sheet) {
            $cells = [];
            $numRows = 0;
            $numCols = 0;
            foreach ($sheet->getRowIterator() as $row) {
                $r = $row->getRowIndex();
                $iterator = $row->getCellIterator();
                $iterator->setIterateOnlyExistingCells(true);
                foreach ($iterator as $cell) {
                    $value = $cell->getFormattedValue();
                    if ($value === '' || $value === null) {
                        continue;
                    }
                    $c = Coordinate::columnIndexFromString($cell->getColumn());
                    $cells[$r][$c] = (string) $value;
                    $numRows = max($numRows, $r);
                    $numCols = max($numCols, $c);
                }
            }
            $this->sheets[$i] = ['cells' => $cells, 'numRows' => $numRows, 'numCols' => $numCols];
        }
        $book->disconnectWorksheets();
    }

    public function val(int $row, int $col, int $sheet = 0): string
    {
        return $this->sheets[$sheet]['cells'][$row][$col] ?? '';
    }

    public function rowcount(int $sheet = 0): int
    {
        return $this->sheets[$sheet]['numRows'] ?? 0;
    }

    public function colcount(int $sheet = 0): int
    {
        return $this->sheets[$sheet]['numCols'] ?? 0;
    }
}
