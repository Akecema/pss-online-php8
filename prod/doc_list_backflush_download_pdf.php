<?php

/**
 * prod/doc_list_backflush_download_pdf.php
 * Part of: Production module
 * Filename suggests: doc list backflush download pdf
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET); generates a PDF (TCPDF/FPDF).
 * Database tables referenced: sys_setup_maintain, request_status, pps_detail_transaction.
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
require_role($dbc, 2);
set_time_limit(0);
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------
//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1'";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Completed)
$sta14 = "SELECT * from request_status WHERE status_id = '14'";
$sta_res14 = mysqli_query($dbc, $sta14);
$rst_sta14 = mysqli_fetch_array($sta_res14);	


$extension = explode('.', $data_setup["logo_name"]);
$filename = $data_setup["logo_comp"] . '.jpg';
$imagePath = '../set_upload/' . $filename;

$dateF = $_GET["date1"];
$dateT = $_GET["date2"];
$factory = $_GET["factory"];
$work_center = $_GET["work_center"];
$plan_no = $_GET["plan_no"];
$shift_ops = $_GET["shift_ops"];

//-------Count all results------------------------//
			
$where_sql = '';
		 
		
//1. DateF 
        if ($dateF  == "0000-00-00" ){
             $wheresql_01 = "";}
        else {
             $wheresql_01 = " AND (MR.date_posting >= '$dateF 00:00:00')";}  
        
// 2. DateT
        if($dateT == "0000-00-00") {
             $wheresql_02 = ""; }
        else {
              $wheresql_02 = " AND (MR.date_posting <= '$dateT 23:59:59')"; }		
             
//3. Factory 
        if ($factory == "NULL"){ 
            $wheresql_03 = ""; }
        else {
            $wheresql_03 = " AND SR.id_factory = '$factory'"; } 
       
  //4. Work Center
        if ($work_center == "NULL" ){
            $wheresql_04 = ""; }
        else {
       $wheresql_04 = " AND MR.work_center = '$work_center'"; }

  //5. Planned Order No.
        if ($plan_no == "NULL"){ 
            $wheresql_05 = ""; }
        else {
            $wheresql_05 = " AND MR.plan_no = '$plan_no'"; }  	
       

//6. Shift
        if ($shift_ops == "NULL"){ 
            $wheresql_06 = ""; }
        else {
       // $wheresql_06 = ""; }
       
            $wheresql_06 = " AND ((MR.shift_pps1 = '$shift_ops') OR (MR.shift_pps2 = '$shift_ops')) "; }  		 			
    
    $where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06;	

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
		$this->Cell(100, 15, 'Backflush Document List', 0, false, 'C', 0, '', 0, false, 'M', 'M');
      $this->SetFont('helvetica', '', 10);
    /*   $tbl =   '<table border="1">
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
      $this->writeHTML($tbl, true, 0, true, 0); */
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
$pdf->SetTitle('Backflush Document List');
$pdf->SetSubject('Backflush Document List');
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



   // set some text to print
   $headerTable = 
   '';

   $pdf->writeHTML($headerTable, true, 0, true, 0);
   $pdf->SetFont('helvetica', '', 10);

   
   $counter = 1;
   $no = 1;
   $sta_out = "";

$queryu = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR, work_center_detail AS SR WHERE MR.work_center = SR.id_work AND (MR.status_pps = '".$rst_sta7["status_desc"]."' OR MR.status_pps = '".$rst_sta14["status_desc"]."' OR MR.status_pps = '".$rst_sta4["status_desc"]."')".$where_sql."ORDER BY MR.plan_no ASC";
$rs = mysqli_query($dbc, $queryu);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?

$a = $num;

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
         <tr align="center" bgcolor="#8ad9f6">
            <th width="30" class="bold">No.</th>
            <th width="80" class="bold">Posting Date</th>
            <th width="70" class="bold">Posting Time</th>
            <th width="180" class="bold">Part No./Part Name</th>
            <th width="120" class="bold">Planned Order No.</th>
            <th width="80" class="bold">Planned Date</th>
            <th width="70" class="bold">Work Center</th>
            <th width="40" class="bold">Shift</th>           
            <th width="60" class="bold">To Location</th>
            <th width="100" class="bold">Document No.</th>
            <th width="80" class="bold">Output Status</th>
            <th width="80" class="bold">Output Qty</th>          
         </tr>
     ';

while ($row = mysqli_fetch_array($rs))
   {
		
	//shift	
		if($row["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($row["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
	
	//quantity output
		if($row["qty_actual"] != "0.000")
	{
		$qty_final = $row["qty_actual"];
	}elseif($row["qty_NG"] != "0.000")
	{
		$qty_final = $row["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	//status output
	//----check string -----------
	
	$sta_out = substr($row["bflush_no"],4,1);
	
	if($sta_out == "1")
	{
		$status_output = "OK";
	}elseif($sta_out == "3")
	{
	    $status_output = "NG";
	}else{
	    // $status_output = "";
	}
	 
	 $sta_out2 = substr($row["bflush_no"],2,3);
			
	if($sta_out2 == "211")
	{
	    $status_output = "OK";
	}elseif($sta_out2 == "221")
	 {
	    $status_output = "NG";
	}else{
	 // $status_output = "";
	}
   
      
      $contentTable .=
      '<tr nobr="true">
         <td width="30" class="text-center">'.$no.'</td>
         <td width="80" class="text-center">'.$row['R2'].'</td>
         <td width="70" class="text-center">'.$row[31].'</td>
         <td width="180" class="text-center">'.$row[9].'</td>
         <td width="120" class="text-center">'.$row[4].'</td>
         <td width="80" class="text-center">'.$row['R'].'</td>       
         <td width="70" class="text-center">'.$row[18].'</td> 
          <td width="40" class="text-center">'.$sta.'</td>
         <td width="60" class="text-center">'.$row[32].'</td>
         <td width="100" class="text-center">'.$row[3].'<br><font color="#FF0000">'.$row[40].'</font></td>
         <td width="80" class="text-center"><font color="#FF9900">'.$status_output.'</font></td>
        <td width="80" class="text-center">'.intval($qty_final).'</td>
      </tr>';

      $counter++;
      $no++;
 $a--;

   }

   $contentTable .= '</table>';

   $pdf->writeHTML($contentTable, true, 0, true, 0);
   
  
   if($a != 0) {
      $pdf->AddPage(); // Stop adding page after reaching max records
   }     
//}

//Close and output PDF document
$pdf->Output('Backflush Document List.pdf', 'I');
