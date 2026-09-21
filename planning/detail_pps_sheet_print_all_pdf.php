<?php

/**
 * planning/detail_pps_sheet_print_all_pdf.php
 * Part of: Planning module
 * Filename suggests: detail pps sheet print all pdf
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET); generates a PDF (TCPDF/FPDF).
 * Database tables referenced: sys_setup_maintain, request_status, work_center_detail, ftp_pps, pps_detail, mat_master_header, mat_master_detail.
 * Includes: config.php, tcpdf.php, tcpdf_barcodes_2d_include.php, tcpdf_barcodes_2d.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 8);
set_time_limit(0);
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------
//CR status (New)
$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16' ";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);

$extension = explode('.', $data_setup["logo_name"]);
$filename = $data_setup["logo_comp"] . '.jpg';
$imagePath = '../set_upload/' . $filename;

$bulan_text = "";
$dateF = date('Y-m-d', strtotime($_GET['srcDateF']));
$dateT = date('Y-m-d', strtotime($_GET['srcDateT']));
$factory = $_GET["srcFactory"];
$work_center = $_GET["srcWC"];
$plan_no = $_GET["srcPlanNo"];
$shift_ops = $_GET["srcShift"];
$status = $_GET["srcStatus"];
$name_file = $_GET["srcNFile"];

$query_convert = "SELECT * FROM `work_center_detail` as SR WHERE SR.id_work = '" . db_esc($dbc, $work_center) . "'";
$result_convert = mysqli_query($dbc, $query_convert);
$row_convert = mysqli_fetch_array($result_convert);

$query_convert2 = "SELECT * FROM `ftp_pps` WHERE file_name = '" . db_esc($dbc, $name_file) . "'";
$result_convert2 = mysqli_query($dbc, $query_convert2);
$row_convert2 = mysqli_fetch_array($result_convert2);

$where_sql = '';
// 1. DateT
if ($dateT == "0000-00-00") {
   $wheresql_01 = "";
} else {
   $wheresql_01 = " AND (MR.date_plan <= '$dateT')";
}
//2. DateF 
if ($dateF  == "0000-00-00") {
   $wheresql_02 = "";
} else {
   $wheresql_02 = " AND (MR.date_plan >= '$dateF')";
}
//3. Factory 
if ($factory == "NULL") {
   $wheresql_03 = "";
} else {
   $wheresql_03 = " AND MR.id_factory_pps = '".db_esc($dbc, $factory)."'";
}
//4. Work Center
if ($work_center == "NULL") {
   $wheresql_04 = "";
} else {
   $wheresql_04 = " AND MR.work_center = '".db_esc($dbc, $work_center)."'";
}
//5. Planned Order No.
if ($plan_no == "NULL") {
   $wheresql_05 = "";
} else {
   $wheresql_05 = " AND MR.plan_no = '".db_esc($dbc, $plan_no)."'";
}
//6. Shift
if ($shift_ops == "NULL") {
   $wheresql_06 = "";
} else {
   // $wheresql_06 = ""; }
   $wheresql_06 = " AND ((MR.shift_pps1 = '".db_esc($dbc, $shift_ops)."') OR (MR.shift_pps2 = '".db_esc($dbc, $shift_ops)."')) ";
}
// 7. Status
if ($status == "NULL") {
   $wheresql_07 = "";
} else {
   $wheresql_07 = " AND MR.status_pps = '".db_esc($dbc, $status)."'";
}
// 8. File name
if ($name_file == "NULL") {
   $wheresql_08 = "";
} else {
   $wheresql_08 = " AND MR.upload_id = '" . db_esc($dbc, $row_convert2["upload_id"]) . "'";
}
$where_sql =  $wheresql_01 . $wheresql_02 . $wheresql_03 . $wheresql_04 . $wheresql_05 . $wheresql_06 . $wheresql_07 . $wheresql_08;
//********** END CONDITION **************

//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');
require_once('../tcpdf/examples/barcodes/tcpdf_barcodes_2d_include.php');
require_once('../tcpdf/tcpdf_barcodes_2d.php');
// require_once('tcpdf_barcodes_2d.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = '../set_upload/3.gif';
		$this->Image($image_file, 5, 10, 88, 12, 'GIF', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 16);
		// Title
		$this->Cell(100, 15, 'Production Planning Sheet', 0, false, 'C', 0, '', 0, false, 'M', 'M');
      $this->SetFont('helvetica', '', 10);
      $tbl =   '<table border="1">
                  <tr>
                     <td>Doc. No.</td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Rev. No.</td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Effective Date</td>
                     <td></td>
                  </tr>
               </table>';
      $this->writeHTML($tbl, true, 0, true, 0);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('PSS Online');
$pdf->SetTitle('Production Planning Sheet');
$pdf->SetSubject('Production Planning Sheet');
$pdf->SetKeywords('TCPDF, PDF');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$queryu = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') AS T, DATE_FORMAT(MR.date_upload,'%d-%m-%Y %H:%i:%s') AS K FROM pps_detail AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND (MR.status_pps != '" . db_esc($dbc, $rst_sta4["status_desc"]) . "' OR MR.status_pps != '" . db_esc($dbc, $rst_sta16["status_desc"]) . "') AND MR.status_pps != '" . db_esc($dbc, $rst_sta["status_desc"]) . "'" . $where_sql . " GROUP BY work_center";
$rs = mysqli_query($dbc, $queryu);
$num_rows = mysqli_num_rows($rs);
$a = $num_rows;

while ($db_rs = mysqli_fetch_array($rs)) {

   if ($db_rs["month_plan"] == "01") {
      $bulan_text = "January";
   } elseif ($db_rs["month_plan"] == "02") {
      $bulan_text = "February";
   } elseif ($db_rs["month_plan"] == "03") {
      $bulan_text = "March";
   } elseif ($db_rs["month_plan"] == "04") {
      $bulan_text = "April";
   } elseif ($db_rs["month_plan"] == "05") {
      $bulan_text = "May";
   } elseif ($db_rs["month_plan"] == "06") {
      $bulan_text = "June";
   } elseif ($db_rs["month_plan"] == "07") {
      $bulan_text = "July";
   } elseif ($db_rs["month_plan"] == "08") {
      $bulan_text = "August";
   } elseif ($db_rs["month_plan"] == "09") {
      $bulan_text = "September";
   } elseif ($db_rs["month_plan"] == "10") {
      $bulan_text = "October";
   } elseif ($db_rs["month_plan"] == "11") {
      $bulan_text = "November";
   } elseif ($db_rs["month_plan"] == "12") {
      $bulan_text = "December";
   } else {
      $bulan_text = "Others";
   }

   //--------------get filename from table ftp_pps
   $query_ftp_pps = "SELECT * FROM ftp_pps WHERE upload_id = '" . db_esc($dbc, $db_rs["upload_id"]) . "'";
   $result_ftp_pps = mysqli_query($dbc, $query_ftp_pps);
   $data_ftp_pps = mysqli_fetch_array($result_ftp_pps);

   // set some text to print
   $headerTable = 
   '<table border="0" >
      <tr>
         <td>
            <table border="1" align="left">
               <tr>
                  <td width="120">Production Plant</td>
                  <td width="10" align="center">:</td>
                  <td width="200">Nilai Plant</td>
               </tr>
               <tr>
                  <td>Factory</td>
                  <td align="center">:</td>
                  <td>Nilai</td>
               </tr>
               <tr>
                  <td>Production Line</td>
                  <td align="center">:</td>
                  <td>'.$db_rs["work_center"].'<br></td>
               </tr>
            </table>
         </td>

         <td width="320"></td>

         <td>
            <table border="1" align="left">
               <tr>
                  <td width="120">Month/Year</td>
                  <td width="10" align="center">:</td>
                  <td width="220">'.$bulan_text.'</td>
               </tr>
               <tr>
                  <td>Date</td>
                  <td width="10" align="center">:</td>
                  <td>'.$db_rs["K"].'</td>
               </tr>
               <tr>
                  <td>File Name</td>
                  <td width="10" align="center">:</td>
                  <td>'.$data_ftp_pps["file_name"].'</td>
               </tr>
            </table>
         </td>
      </tr>
   </table>';

   $pdf->writeHTML($headerTable, true, 0, true, 0);
   $pdf->SetFont('helvetica', '', 10);

   $contentTable = 
   '<style>
      .text-center {
         text-align: center;
      }
      .bold {
         font-weight: bold;
      }
   </style>

   <table border="1">
      <thead>
         <tr align="center">
            <th width="30" class="bold">No.</th>
            <th width="70" class="bold">Model</th>
            <th width="230" class="bold">Part No./Part Name</th>
            <th width="100" class="bold">Planned Order No.</th>
            <th width="70" class="bold">Planned Start Date</th>
            <th width="40" class="bold">Shift</th>
            <th width="40" class="bold">Seq#</th>
            <th width="60" class="bold">Planned<br>Order<br>Quantity</th>
            <th width="40" class="bold">UOM</th>
            <th width="100" class="bold">2D Barcode</th>
            <th width="90" class="bold">Status</th>
            <th width="145" class="bold" colspan="3">Production Timing</th>
         </tr>
      </thead>';

   $query_by_group = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') AS T, DATE_FORMAT(MR.date_upload,'%d-%m-%Y %H:%i:%s') AS K from pps_detail AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.work_center = '" . db_esc($dbc, $db_rs["work_center"]) . "' " . $where_sql . " ";
   $result_by_group = mysqli_query($dbc, $query_by_group);   //run the query.

   $counter = 1;
   $no = 1;
   $i = 1;

   while ($row = mysqli_fetch_array($result_by_group)) {
      $no = sprintf('%03d', $no);
      if ($row["shift_pps1"] != "") {
         $sta = "D/S";
      } elseif ($row["shift_pps2"] != "") {
         $sta = "N/S";
      } else {
         $sta = " ";
      }
      //---------get material header---------
      $query_mat_h = "SELECT * FROM mat_master_header AS HD, mat_master_detail AS AD WHERE HD.material_no = AD.material AND HD.material_no = '" . db_esc($dbc, $row['material_no']) . "'";
      $result_mat_h = mysqli_query($dbc, $query_mat_h);
      $data_mat_h = mysqli_fetch_array($result_mat_h);

      //---------get material detail---------
      $query_mat_d = "SELECT * FROM mat_master_detail WHERE (material = '" . db_esc($dbc, $row['material_no']) . "' OR bill_component = '" . db_esc($dbc, $row['material_no']) . "')";
      $result_mat_d = mysqli_query($dbc, $query_mat_d);
      $data_mat_d = mysqli_fetch_array($result_mat_d);
      ++$i;

      // set the barcode content and type
      $bar_text = ($row["material_no"] . '|' . $row["plan_no"] . '|' . $row["date_plan"] . '|' . $row["work_center"] . '|' . $sta . '|' . $row["qty_plan"] . '|' . $data_mat_h["BUn"]);
      $params = $pdf->serializeTCPDFtagParameters(
         array($bar_text, 'PDF417', '', '', 25, '', '', 'N')
      );

      $contentTable .=
      '<tr nobr="true">
         <td width="30" class="text-center">'.$no.'</td>
         <td width="70" class="text-center">'.$row["model_code"].'</td>
         <td width="230"><b>'.$row["material_no"].'</b><br>'.$data_mat_h["material_desc"].'</td>
         <td width="100" class="text-center">'.$row["plan_no"].'</td>
         <td width="70" class="text-center">'.$row["T"].'</td>
         <td width="40" class="text-center">'.$sta.'</td>
         <td width="40" class="text-center">'.$row["seq_pps"].'</td>
         <td width="60" class="text-center">'.intval($row["qty_plan"]).'</td>
         <td width="40" class="text-center">'.$data_mat_h["BUn"].'</td>
         <td width="100" class="text-center"><tcpdf method="write2DBarcode" params="'.$params.'" /></td>
         <td width="90" class="text-center"></td>
         <td width="48"></td>
         <td width="48"></td>
         <td width="48"></td>
      </tr>';

      $counter++;
      $no++;
   }

   $contentTable .= '</table>';

   $pdf->writeHTML($contentTable, true, 0, true, 0);
   
   $a--;
   if($a != 0) {
      $pdf->AddPage(); // Stop adding page after reaching max records
   }     
}

//Close and output PDF document
$pdf->Output('Production_Planning_Sheet.pdf', 'I');
