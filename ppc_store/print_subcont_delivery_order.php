<?php
/*require('C:\xampp\htdocs\PSS_Online\ppc_store\fpdf\fpdf.php');
*/
require('\fpdf\fpdf.php');
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();
$username = $_SESSION['username'];
include '../include/config.php';


$uid = base64_decode(($_GET["uid"]));
//$uid = $_GET["uid"];

//Initialize the 5 columns and the total
$column_item = "";
$column_part_no = "";
$column_part_name = "";
$column_qty = "";
$column_unit = "";
$no = 1;
$max = 15;

//-------select data from database --------------------------//

    $query_detail = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') AS T5, DATE_FORMAT(date_generate_tp,'%d-%m-%Y') AS T15 FROM tp_subcont_detail WHERE status_tran = 'Y' AND doc_tp = '".$uid."' GROUP BY doc_tp";
    $result_detail = mysql_query($query_detail) or die (mysql_error());
	$row_detail = mysql_fetch_array($result_detail);
 
    $query2 = "SELECT * FROM tp_subcont_detail WHERE status_tran = 'Y' AND doc_tp = '".$uid."' ORDER BY doc_tp";
    $result2 = mysql_query($query2) or die (mysql_error());
	$num = mysql_num_rows($result2);  
    
	$query_vendor = "SELECT * FROM vendor_detail WHERE vendor_code = '".$row_detail["sloc_to"]."'";
	$result_vendor = mysql_query($query_vendor) or die (mysql_error());
	$row_vendor = mysql_fetch_array($result_vendor);
	
	

//Create a new PDF file
$pdf=new FPDF('P','mm','A4');
$pdf->SetMargins(6.5, 6.5, 6.5);

class PDF extends FPDF
{
// Page header
function Header()
{

 
/*$this->Image('set_upload\3.gif',10,6,100);	*/// Logo
$this->SetFont('Arial','B',12);
$this->Cell(100,3,'INGRESS PRECISION SDN. BHD.',0,1);
$this->SetFont('Arial','',7);
$this->Cell(100,3,'PT. 2475-2476, Kawasan Perindustrial Nilai, P.O. Box 45, 71807 Nilai, Negeri Sembilan, Malaysia.',0,1);
$this->Cell(100,3,'Tel : 06-799 5599(10 lines) Fax: 06-799 5597, 799 5596',0,1);
$this->Cell(100,3,'_________________________________________________________________________________________________________________________________________',0,1);
  
    $this->Ln(10); // Line break

}

// Page footer
function Footer()
{
	$this->Ln(3);// Line break 
    $this->SetY(-65);// Position at 6.0 cm from bottom
    $this->SetFont('Arial','',10);// Arial italic 8
	$this->Cell(0,7,'________________________________________________________________________________________________',0,1);
	$this->Cell(0,7,'Received the above mentioned goods in good order and condition.',0,1,'L');
	$this->Cell(0,7,'Authorised by :',0,1,'C','','');
	$this->SetFont('Arial','',7);
	$this->Cell(270,3,'INGRESS PRECISION SDN. BHD.',0,1,'C');
	$this->Cell(270,3,'PT. 2475-2476, Kawasan Perindustrial Nilai,',0,1,'C');
	$this->Cell(270,3,'P.O. Box 45, 71807 Nilai,',0,1,'C');
    $this->Cell(270,3,'Negeri Sembilan, Malaysia.',0,1,'C');
	$this->Cell(270,3,'Tel : 06-799 5599. Fax: 06-799 5598',0,1,'C');
    $this->SetFont('Arial','',10);// Arial italic 8
	$this->Cell(0,7,'Received by ',0,1);
	$this->Cell(0,7,'Name : ________________________________________',0,1);
    $this->Ln(3);// Line break
    $this->Cell(0,10,''.$this->PageNo().' of {nb}',0,0,'C'); // Page number
}





} // end class






// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',18);
$pdf->Cell(60,10,'SUBCONTRACTOR DELIVERY ORDER',0,2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(150,0,'No. :',0,1,'R','','');
$pdf->Cell(180,0,$uid,0,1,'R','','');
$pdf->Cell(150,8,'Date :',0,1,'R');
$pdf->Cell(180,-8,$row_detail["T5"],0,1,'R','','');
$pdf->Ln(10);

//Fields Name position
$Y_Fields_Name_position = 80;
//Table position, under Fields Name
$Y_Table_Position = 86;


//-----address vendor -------
$pdf->SetFont('Arial','B',10);
$pdf->Cell(140,0,'Deliver to. :',0,1,'L','','');
$pdf->Ln(2);
$pdf->Cell(170,8,$row_vendor["vendor_name"],0,1,'L','','');
$pdf->Cell(170,0,$row_vendor["add_no1"],0,1,'L','','');
$pdf->Cell(170,7,$row_vendor["post_code"].','.$row_vendor["post_city"].',',0,1,'L','','');
$pdf->Cell(170,2,$row_vendor["post_region"].','.$row_vendor["post_country"].'.',0,1,'L','','');

$grd_total = 0.000;

//For each row, add the field to the corresponding column
while($row = mysql_fetch_array($result2))
{	

	$no = sprintf('%04d',$no);  // item no
	
    $item_no = $no;
    $part_no = substr($row["material_no"],0,20);
    $part_desc = $row["material_desc"];
    $part_qty = $row["qty_tp"];
	$part_uom = $row["uom"];
	
	
	  
	$grd_total = ($grd_total + $row["qty_tp"]);
	
	$query_model = "SELECT * FROM mat_master_header WHERE material_no = '".$part_no."'";
	$result_model = mysql_query($query_model) or die (mysql_error());
	$row_model = mysql_fetch_array($result_model);

	$model = $row_model["material_group"];

    $column_item = $column_item.$item_no."\n";
    $column_part_no = $column_part_no.$part_no."\n";
	$column_part_name = $column_part_name.$part_desc."\n";
    $column_qty = $column_qty.$part_qty."\n";
	$column_unit = $column_unit.$part_uom."\n";
	$column_model = $column_model.$model."\n";
	$column_total = $column_total.$grd_total."\n";
	
	
	if($no % $max == 1)  // next pages
	{
		
		if($no != 1)  // not 1st page [doc no]
		{
			
			
	$pdf->SetFont('Arial','B',18);
	$pdf->Cell(60,10,'SUBCONTRACTOR DELIVERY ORDER',0,2);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(150,0,'No. :',0,1,'R','','');
	$pdf->Cell(180,0,$uid,0,1,'R','','');
	$pdf->Cell(150,8,'Date :',0,1,'R');
	$pdf->Cell(180,-8,$row_detail["T5"],0,1,'R','','');
	$pdf->Ln(10);
	
	//-----address vendor -------
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(140,0,'Deliver to. :',0,1,'L','','');
	$pdf->Ln(2);
	$pdf->Cell(170,8,$row_vendor["vendor_name"],0,1,'L','','');
	$pdf->Cell(170,0,$row_vendor["add_no1"],0,1,'L','','');
	$pdf->Cell(170,7,$row_vendor["post_code"].','.$row_vendor["post_city"].',',0,1,'L','','');
	$pdf->Cell(170,2,$row_vendor["post_region"].','.$row_vendor["post_country"].'.',0,1,'L','','');
	
	 	}
	
	//----header table ---------
	$pdf->SetFillColor(193,229,252); 
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY($Y_Fields_Name_position);
	$pdf->SetX(1);
	$pdf->Cell(15,6,'Item',1,0,'C',1);
	$pdf->SetX(15);
	$pdf->Cell(60,6,'Part No.',1,0,'L',1);
	$pdf->SetX(60);
	$pdf->Cell(160,6,'Description',1,0,'L',1);
	$pdf->SetX(142);
	$pdf->Cell(30,6,'Model',1,0,'L',1);
	$pdf->SetX(162);
	$pdf->Cell(30,6,'Quantity',1,0,'L',1);
	$pdf->SetX(192);
	$pdf->Cell(17,6,'Unit',0,1);
	
	}
	//---content table --------
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(0);
	$pdf->Cell(15,0,$item_no,0,0,'C');
	$pdf->SetX(15);
	$pdf->Cell(60,0,$part_no,0);
	$pdf->SetX(60);
	$pdf->Cell(160,0,$part_desc,0);
	$pdf->SetX(142);
	$pdf->Cell(30,0,$model,0,0,'L');
	$pdf->SetX(162);
	$pdf->Cell(30,0,$part_qty,0,0,'R');
	$pdf->SetX(183);
	$pdf->Cell(30,0,$part_uom,0,0,'C');
	


    //---- pages -----
		if($no % $max == 0)
		{
				 
		$pdf->addPage('','',false); 
		
		} // end if
   
    $no++;
   }


for($i=1;$i<=$num;$i++)
{

//------driver detail ----------
if($i == $num)
{
	
$pdf->SetFont('Arial','B',10);
$pdf->SetY(-106);
$pdf->Cell(191,8,'__________________',0,1,'R');
$pdf->SetY(-100);
$pdf->Cell(185,8,'TOTAL        :   '.number_format($grd_total, 3,'.','').'    PCS',0,1,'R');
$pdf->SetY(-97);
$pdf->Cell(191,8,'__________________',0,1,'R');
$pdf->SetY(-96);
$pdf->Cell(191,8,'__________________',0,1,'R');

/*$pdf->Cell(100,7,'TOTAL             : '.$row_detail["driver_by"].'',0,1,'L');*/
	
$pdf->SetFont('Arial','',10);
$pdf->SetY(-90);
$pdf->Cell(10,7,'Driver Name             : '.$row_detail["driver_by"].'',0,1,'L');
$pdf->Cell(10,7,'Transport Plate No.  : '.$row_detail["plate_no"].'',0,1,'L');
$pdf->Cell(10,7,'Time                         : '.$row_detail["posting_time"].' '.date("A", strtotime($row_detail["posting_time"])),0,1,'L');
$pdf->Cell(270,42,' '.$row_detail["prepared_by"].'',0,1,'C');


} // end if
} // end for


mysql_close();


$pdf->Output();
?>


