<?php


/**
 * prod/findMaterialType.php
 * Part of: Production module
 * Filename suggests: findMaterialType
 *
 * Behavior: reads parameters from the query string ($_GET).
 * Database tables referenced: sys_setup_maintain, table_material.
 * Includes: config.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
$material_type = $_GET['material_type'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 2);

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

$query9 = "SELECT * FROM table_material WHERE mat_type = ? AND bom_status = 'Y' ORDER BY material_no ASC"; $query9_args = [$material_type];
$result9 = db_query_bind($dbc, $query9, $query9_args);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="../img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/uniform.css" />
<link rel="stylesheet" href="../css/select2.css" />
<link rel="stylesheet" href="../css/matrix-style.css" />
<link rel="stylesheet" href="../css/matrix-media.css" />
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/jquery.gritter.css" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>
</head>
<body>
   <div id="material_nodiv">
   <select name="material_no" id="material_no" class="span5">
   <option value="NULL" placeholder="Select Part No."> -- Select Part No. --</option>
	<?php
    while($row9=mysqli_fetch_array($result9, MYSQLI_NUM)) 
    {
          
    ?>
    <option value="<?php echo h($row9[1]); ?>" > <?php echo h($row9[1]); ?> - <?php echo h($row9[2]); ?></option>
    
    <?php     }
    
    ?>
</select></div>


<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.tables.js"></script>
</body>
</html>