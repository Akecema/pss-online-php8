<?php
// Plain-PHP assertion harness (IQIMS rule 14): php tests/test_libraries.php
// Guards the Composer-managed libraries and the shim files that forward to them.
declare(strict_types=1);

$fail = 0;
function check(string $name, bool $ok): void
{
    global $fail;
    echo ($ok ? 'PASS ' : 'FAIL ') . $name . "\n";
    if (!$ok) { $fail++; }
}

$root = dirname(__DIR__);
check('vendor/autoload.php exists (run composer install)', is_file($root . '/vendor/autoload.php'));
require_once $root . '/vendor/autoload.php';

// --- PhpSpreadsheet (replaced PHPExcel 1.7.8, which does not load on PHP 8) ---
check('PhpSpreadsheet IOFactory available', class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class));
$book = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$book->getActiveSheet()->fromArray([['ID', 'Name'], [7, 'Bolt & Nut']], null, 'A1');
$tmp = tempnam(sys_get_temp_dir(), 'xl') . '.xlsx';
(new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($book))->save($tmp);
$rows = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmp, 0, ['Xlsx', 'Xls', 'Csv'])->getActiveSheet()->toArray(null, true, true, true);
check('toArray(null,true,true,true) keeps the row/letter layout the upload pages index into',
    ($rows[2]['A'] ?? null) == 7 && ($rows[2]['B'] ?? null) === 'Bolt & Nut' && ($rows[1]['B'] ?? null) === 'Name');
$notSheet = tempnam(sys_get_temp_dir(), 'xl');
file_put_contents($notSheet, '<?php echo "not a spreadsheet";');
try {
    \PhpOffice\PhpSpreadsheet\IOFactory::load($notSheet, 0, ['Xlsx', 'Xls']);
    $rejected = false;
} catch (Throwable $e) {
    $rejected = true;
}
check('a non-spreadsheet is rejected when only Excel types are allowed', $rejected);
unlink($tmp);
unlink($notSheet);

// --- planning/excel_reader2.php: old Spreadsheet_Excel_Reader layout on top of PhpSpreadsheet ---
require_once $root . '/planning/excel_reader2.php';
$xls = tempnam(sys_get_temp_dir(), 'xl') . '.xls';
$b2 = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$b2->getActiveSheet()->fromArray([['DATE', '2/19/2018'], [null, null], ['D87A', 'PW1', 'part']], null, 'A1');
(new \PhpOffice\PhpSpreadsheet\Writer\Xls($b2))->save($xls);
$reader = new Spreadsheet_Excel_Reader($xls);
check('excel_reader2 adapter: 1-based [row][col], empty cells and rows absent',
    ($reader->sheets[0]['cells'][1][2] ?? null) === '2/19/2018'
    && !isset($reader->sheets[0]['cells'][2])
    && ($reader->sheets[0]['cells'][3][3] ?? null) === 'part'
    && $reader->val(3, 1) === 'D87A' && $reader->rowcount() === 3);
$missing = new Spreadsheet_Excel_Reader('/nonexistent/file.xls');
check('excel_reader2 adapter: unreadable file gives no sheets, not a crash', $missing->sheets === [] && $missing->error !== '');
unlink($xls);

// --- TCPDF / FPDF via shims ---
foreach (['admin', 'prod', 'planning', 'qqc_super', 'tcpdf'] as $dir) {
    $shim = $root . '/' . $dir . '/tcpdf_barcodes_2d.php';
    check("$dir/tcpdf_barcodes_2d.php is a small forwarding shim",
        is_file($shim) && filesize($shim) < 1024 && str_contains((string) file_get_contents($shim), 'vendor/tecnickcom/tcpdf'));
}
check('no per-folder copy of TCPDF internals is left', !is_file($root . '/admin/include/tcpdf_static.php') && !is_dir($root . '/prod/fonts'));
require_once $root . '/admin/tcpdf_barcodes_2d.php';
$svg = (new TCPDF2DBarcode('72950T8CT010M1', 'PDF417'))->getBarcodeSVGcode(1.0, 0.8, 'black');
check('PDF417 barcode renders as SVG (the old copy crashed here on PHP 8)', str_starts_with($svg, '<?xml') && str_contains($svg, '<svg') && strlen($svg) > 10000);
$long = (new TCPDF2DBarcode(str_repeat('0123456789', 6), 'PDF417'))->getBarcodeArray();
check('long numeric barcode works (needs the bcmath extension)', extension_loaded('bcmath') && ($long['num_rows'] ?? 0) > 0);

require_once $root . '/ppc_store/fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Delivery order');
check('FPDF produces a PDF', str_starts_with($pdf->Output('S'), '%PDF-'));

// --- TCPDF <tcpdf> tag: only signed, whitelisted calls run ---
define('K_TCPDF_CALLS_IN_HTML', true);
define('K_ALLOWED_TCPDF_TAGS', '|write2DBarcode|');
require_once $root . '/tcpdf/tcpdf.php';
$t = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
$t->setPrintHeader(false);
$t->setPrintFooter(false);
$t->AddPage();
$tag = $t->serializeTCPDFtag('write2DBarcode', ['ABC123', 'PDF417', '', '', 25, '', [], 'N']);
$t->writeHTML('<table><tr><td><tcpdf data="' . $tag . '" /></td></tr></table>', true, false, true, false, '');
check('signed barcode tag renders inside HTML and yields a PDF', str_starts_with($t->Output('test.pdf', 'S'), '%PDF-'));

exit($fail === 0 ? 0 : 1);
