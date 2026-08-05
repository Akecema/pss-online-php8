<?php 

/**
 * xindex_v0.php
 * Part of: Core / entry-point script
 * Filename suggests: xindex v0
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST); sends email.
 * Database tables referenced: user_detail, failed_login, status_failed.
 * Includes: con-dbcIPSB.php, ckies-brw.php, index_admin.php, backjob_clean.php, index_super.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
include 'con-dbcIPSB.php';

?><!DOCTYPE html>
<html lang="en">
<head>

        <title><?php echo $data_setup["title_desc"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="img/favicon.ico">
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/matrix-login.css" />
        <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="scripts/thickbox.css" type="text/css" media="screen" />
		<style type="text/css">
		body,td,th {
	font-family: "Open Sans", sans-serif;
}
body {
	background-color: #FFFFFF;
}
        </style>
		<script type="text/javascript" src="javascript/jquery-latest.js"></script> 
		<script type="text/javascript" src="javascript/thickbox.js"></script>	
		<script language="javascript">

 defaultStatus = "MRIN Online  <?php echo $data_setup['title_desc']; ?>"
 function show ( text )
 {
  window.status=text;
  return true;
 }
</script>
 <?php 
	   
	include 'ckies-brw.php';
	
	?>    </head>
    <body> 
    <p align="center"> <img src="set_upload/<?php echo $filename;  ?>" /><br>
    </p>
    <?php 

 //Checks if there is a login cookie
 if(isset($_COOKIE['ID_my_site']))

 //if there is, it logs you in and directes you to the members page
 { 
 
 
 //-----------------baru tambah
 
 //unset($_SESSION["username"]);  
		session_unset();
	//	session_destroy();   
		
		$past = time() - 100; 
   //this makes the time in the past to destroy the cookie 
     setcookie("ID_my_site", $past); 
     setcookie("Key_my_site", $past); 
 

//------------------------barutambah 03/04/2014
 
 
 	$username = $_COOKIE['ID_my_site']; 
 	$pass = $_COOKIE['Key_my_site'];
 	 	$check = mysqli_query($dbc, "SELECT * FROM user_detail WHERE username = '$username' AND status = 'AC' AND status_failed = 'N'")or die(mysqli_error($dbc));
 	while($info = mysqli_fetch_array($check)) 	
 		{
 		if ($pass != $info['password']) 
 			{
				
	   //-------------additional for checking failed login 5 times ---------------
		//-------------edit date 16/11/2017
		
		$check_log = "SELECT * FROM failed_login AS FL, user_detail AS UL WHERE FL.staff_ID = UL.staff_ID AND FL.username = '".$_POST['username']."' AND FL.ip_address = '".$_SERVER["REMOTE_ADDR"]."'  AND FL.date_failed BETWEEN DATE_SUB(NOW() , INTERVAL 1 DAY) AND NOW()";
		$rs_check_log = mysqli_query($dbc, $check_log);   
	    $num_check_log = mysqli_num_rows($rs_check_log);  
		$row = mysqli_fetch_array($rs_check_log);
		
		if($num_check_log < 2)
		{
			
				
		$query_log = "INSERT INTO failed_login(ip_address,date_failed,staff_ID,username) VALUES('".$_SERVER["REMOTE_ADDR"]."',NOW(),'".$info['staff_ID']."','".$_POST['username']."')";
		$result_log = mysqli_query($dbc, $query_log) or die (mysqli_error($dbc));
		
		             
		             echo "<script>";
			         echo "alert('Incorrect password, please try again.');";
		             echo "window.location='index.php'";
					 echo "</script>";
			
		}else{
		
		//------update status_failed -------------------
		//------hantar e-mail kpd administrator---------
		
		//----------------------------------------------	
			       $query_update_fail = "UPDATE user_detail SET status_failed = 'Y', date_failed = NOW(), user_update = '".$row["username"]."', date_update = NOW() where username='".$_POST["username"]."'";
				  $result_update_fail = mysqli_query($dbc, $query_update_fail) or die (mysqli_error($dbc));
				  
				  if(mysqli_affected_rows($dbc) == 1) { //If it ran ok
				  
			
			$to = $row["user_email"]; 
			$subject = "PSS Online Reset Account password changed."; 
			$headers = "From: " .$data_setup["email_account"]."\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			
			$mess2 ="<p>Dear Sir; </br>";
			
			$mess2 .="<p>Access to the web page was blocked. Details of the reset password changed as below;</p>";
			$mess = '<html><body>';
			$mess .= '<table cellpadding="5">';
			$mess .= "<tr><td><strong>Username :</strong> </td><td>" .$_POST["username"].  "</td></tr>";		
			$mess .= "<tr><td><strong>Staff ID :</strong> </td><td>" .$info['staff_ID']. "</td></tr>";
		    $mess .= "</table>";	
			$mess .= "<br>"; 
		
			$mess .="<p>Please use the following link to view:</br>";
			$mess .="<a href='".$data_setup["urls_system"]."'>" .$data_setup["urls_system"]."</a> </p>";
			$mess .= "<p><font color='black'>This is a system generated email. Please DO NOT reply. </font></p>";
			$mess .= "<p>&nbsp;</p>";
			$mess .= "</body></html>";
			
			mail($to, $subject, $mess2.$mess, $headers);
		
				  }
		
		
		             echo "<script>";
			         echo "alert('You have tried more than 3 invalid attempts.');";
		             echo "window.location='login_lock.php'";
					 echo "</script>";	
					
		
		}
				
				
 			 			}
 		else
 			{
			
 			if ($info['level_id']== 1)
		{
		// session 'index_admin.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;

		//include 'index_admin.php'; 
		$url = "index_admin.php";
			if($ua['name'] == "Google Chrome")
	{
           header ("Location: ".$data_setup["urls_system"]."/admin/index_admin.php?page=".encode($url,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin/index_admin.php?page=".encode($url,12));
		
		
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/admin/index_admin.php");	
	}	
		}// display admin screen
		
	elseif ($info['level_id']== 2)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
 
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;

 include 'backjob_clean.php';

		$url2 = "index_production.php";
		if($ua['name'] == "Google Chrome")
	{

               header ("Location: ".$data_setup["urls_system"]."/prod/index_production.php?page=".encode($url2,12));	

		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/prod/index_production.php?page=".encode($url2,12));
	}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/prod/index_production.php");	
	}		
		}//display user screen
		
	elseif ($info['level_id']== 3)
		{

$username = $_POST["username"]; 
$password = $_POST["pass"];
 
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;

 include 'backjob_clean.php';

		$url3 = "index_production_super.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/prod_super/index_production_super.php?page=".encode($url3,12));
               //header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/prod_super/index_production_super.php?page=".encode($url3,12));
	}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/prod_super/index_production_super.php");	
	}		
		}//display production manager screen	

   elseif ($info['level_id']== 4)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		//include 'index_super.php';
		$url4 = "index_ppc.php";
		if($ua['name'] == "Google Chrome")
	{
                 header ("Location: ".$data_setup["urls_system"]."/ppc/index_ppc.php?page=".encode($url4,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc/index_ppc.php?page=".encode($url4,12));
		
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc/index_ppc.php");	
	}	
		}//display user screen
  
elseif ($info['level_id']== 5)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		
		$url5 = "index_ppc_super.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc_super/index_ppc_super.php?page=".encode($url5,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc_super/index_ppc_super.php?page=".encode($url5,12));
		
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc_super/index_ppc_super.php");	
	}	
		}//display user screen
		elseif ($info['level_id']== 6)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		
		$url6 = "index_supply.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/supply/index_supply.php?page=".encode($url6,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/supply/index_supply.php?page=".encode($url6,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/supply/index_supply.php");	
	}	
		}//display wip supply
		elseif ($info['level_id']== 7)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url7 = "index_supply_super.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/supply_super/index_supply_super.php?page=".encode($url7,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/supply_super/index_supply_super.php?page=".encode($url7,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/supply_super/index_supply_super.php");	
	}	
		}//display wip superuser
		
		elseif ($info['level_id']== 8)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;

include 'backjob_clean.php';
	
		$url8 = "index_planning.php";
		if($ua['name'] == "Google Chrome")
	{	header ("Location: ".$data_setup["urls_system"]."/planning/index_planning.php?page=".encode($url8,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/planning/index_planning.php?page=".encode($url8,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/planning/index_planning.php");	
	}	
		}//display planning dept
	   elseif ($info['level_id']== 9)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
	include 'backjob_clean.php';
	
		$url9 = "index_planning_super.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/planning_super/index_planning_super.php?page=".encode($url9,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/planning_super/index_planning_super.php?page=".encode($url9,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/planning_super/index_planning_super.php");	
	}	
		}//display planning super 
   elseif ($info['level_id']== 10)
		{
		
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url10 = "index_qqc.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/qqc/index_qqc.php?page=".encode($url10,12));	

		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/qqc/index_qqc.php?page=".encode($url10,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/qqc/index_qqc.php");	
	}	
		}//display QA/QC dept
	 elseif ($info['level_id']== 11)
		{
		
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url11 = "index_qqc_super.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/qqc_super/index_qqc_super.php?page=".encode($url11,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/qqc_super/index_qqc_super.php?page=".encode($url11,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/qqc_super/index_qqc_super.php");	
	}	
		}//display QA/QC superadmin dept	
	 elseif ($info['level_id']== 12)
		{
		
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url12 = "index_ppc_store.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc_store/index_ppc_store.php?page=".encode($url12,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc_store/index_ppc_store.php?page=".encode($url12,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc_store/index_ppc_store.php");	
	}	
		}//display ppc store				
		else{ 
		
		 	if($ua['name'] == "Google Chrome")
	    {
		  header ("Location: ".$data_setup["urls_system"]."/blankPg.php");	
               //header("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/blankPg.php"); 
		
		
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	    {
		
	    header ("Location: ".$data_setup["urls_system"]."/blankPg.php");	
		
	     }	
		
 			}
 		}
}
 }

 //if the login form is submitted 
 if (isset($_POST['submit'])) { // if form has been submitted

 // makes sure they filled it in
 	if(!$_POST['username'] | !$_POST['pass']) {
	
	                 echo "<br><br>"; 
		             echo "<script>";
			         echo "alert('You did not fill in a required field.');";
		             echo "window.location='index.php'";
					 echo "</script>";
	
 		//die('You did not fill in a required field.');
 	}
 	// checks it against the database

 	
 	$check = mysqli_query($dbc, "SELECT * FROM user_detail WHERE username = '".$_POST['username']."' AND status = 'AC' AND status_failed = 'N'")or die(mysqli_error($dbc));

 //Gives error if user dosen't exist
 $check2 = mysqli_num_rows($check);
 if ($check2 == 0) {

 
                     echo "<br><br>"; 
		             echo "<script>";
			         echo "alert('Access denied. Kindly contact System Administrator.');";
		             echo "window.location='index.php'";
					 echo "</script>";
 
 
 		//die('That user does not exist in our database. Please contact IAV Administrator to Register.');
 				}
 while($info = mysqli_fetch_array($check)) 	
 {
    $_POST['pass'] = stripslashes($_POST['pass']);
 	$info['password'] = stripslashes($info['password']);
 	$_POST['pass'] = md5($_POST['pass']);

 //gives error if the password is wrong
 	if ($_POST['pass'] != $info['password']) {
	
	
		
		//-------------additional for checking failed login 5 times ---------------
		//-------------edit date 16/11/2017
		
		$check_log = "SELECT * FROM failed_login AS FL, user_detail AS UL WHERE FL.staff_ID = UL.staff_ID AND FL.username = '".$_POST['username']."' AND FL.ip_address = '".$_SERVER["REMOTE_ADDR"]."'  AND FL.date_failed BETWEEN DATE_SUB( NOW() , INTERVAL 1 DAY ) AND NOW()";
		$rs_check_log = mysqli_query($dbc, $check_log);   
	    $num_check_log = mysqli_num_rows($rs_check_log);  
		$row = mysqli_fetch_array($rs_check_log);
		
		if($num_check_log < 2)
		{
			
				
		$query_log = "INSERT INTO failed_login(ip_address,date_failed,staff_ID,username) VALUES('".$_SERVER["REMOTE_ADDR"]."',NOW(),'".$info['staff_ID']."','".$_POST['username']."')";
		$result_log = mysqli_query($dbc, $query_log) or die (mysqli_error($dbc));
		
		             
		             echo "<script>";
			         echo "alert('Incorrect password, please try again.');";
		             echo "window.location='index.php'";
					 echo "</script>";
			
		}else{
		
		//------update status_failed -------------------
		//------hantar e-mail kpd administrator---------
		
		//----------------------------------------------	
			       $query_update_fail = "UPDATE user_detail SET status_failed = 'Y', date_failed = NOW(), user_update = '".$row["username"]."', date_update = NOW() where username='".$_POST["username"]."'";
				  $result_update_fail = mysqli_query($dbc, $query_update_fail) or die (mysqli_error($dbc));
				  
				  if(mysqli_affected_rows($dbc) == 1) { //If it ran ok
				  
			
			$to = $row["user_email"]; 
			$subject = "PSS Online Reset Account password changed."; 
			$headers = "From: " .$data_setup["email_account"]."\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			
			$mess2 ="<p>Dear Sir; </br>";
			
			$mess2 .="<p>Access to the web page was blocked. Details of the reset password changed as below; </p>";
			$mess = '<html><body>';
			$mess .= '<table cellpadding="5">';
			$mess .= "<tr><td><strong>Username :</strong> </td><td>" .$_POST["username"].  "</td></tr>";		
			$mess .= "<tr><td><strong>Staff ID :</strong> </td><td>" .$info['staff_ID']. "</td></tr>";
		    $mess .= "</table>";	
			$mess .= "<br>"; 
		
			$mess .="<p>Please use the following link to view:</br>";
			$mess .="<a href='".$data_setup["urls_system"]."'>" .$data_setup["urls_system"]."</a> </p>";
			$mess .= "<p><font color='black'>This is a system generated email. Please DO NOT reply. </font></p>";
			$mess .= "<p>&nbsp;</p>";
			$mess .= "</body></html>";
			
			mail($to, $subject, $mess2.$mess, $headers);
		
				  }
		
		
		             echo "<script>";
			         echo "alert('You have tried more than 3 invalid attempts.');";
		             echo "window.location='login_lock.php'";
					 echo "</script>";	
					
		
		}
		
	//--------------------end checking login failed 5 time-------------------------------------------------                 
 		
 	}
	else 
 { 
 
 // if login is ok then we add a cookie 
 	 $_POST['username'] = stripslashes($_POST['username']); 
 	 $hour = time() + 3600; 
 setcookie('ID_my_site', $_POST['username'], $hour); 
 setcookie('Key_my_site', $_POST['pass'], $hour);	 
 
 //then redirect them to the members area 
if ($info['level_id']== 1)
		{

// session 'index_admin.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		//include 'index_admin.php'; 
		$url = 'index_admin.php'; 
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/admin/index_admin.php?page=".encode($url,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/admin/index_admin.php?page=".encode($url,12));
		//header ("Location: ".$data_setup["urls_system"]."/admin/index_admin.php?page=".encode($url,6));
	}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/admin/index_admin.php");	
	}	
		
		}// display admin screen
	elseif ($info['level_id']== 2)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];

session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		
		include 'backjob_clean.php';
		
		$url2 = "index_production.php";
		if($ua['name'] == "Google Chrome")
	{

                 header ("Location: ".$data_setup["urls_system"]."/prod/index_production.php?page=".encode($url2,12));	

		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/prod/index_production.php?page=".encode($url2,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/prod/index_production.php");	
	}	
		
		}//display user screen
		elseif ($info['level_id']== 3)
		{
$username = $_POST["username"]; 
$password = $_POST["pass"];

session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		
		include 'backjob_clean.php';
		
		$url3 = "index_production_super.php";
		if($ua['name'] == "Google Chrome")
	{
                header ("Location: ".$data_setup["urls_system"]."/prod_super/index_production_super.php?page=".encode($url3,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/prod_super/index_production_super.php?page=".encode($url3,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/prod_super/index_production_super.php");	
	}	
		
		}//display production manager screen
		elseif ($info['level_id']== 4)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		//include 'index_super.php';
		$url4 = "index_ppc.php";
		if($ua['name'] == "Google Chrome")
	{
               header ("Location: ".$data_setup["urls_system"]."/ppc/index_ppc.php?page=".encode($url4,12));	

		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc/index_ppc.php?page=".encode($url4,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc/index_ppc.php");	
	}	
		}//display ppc screen
	elseif ($info['level_id']== 5)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
		//include 'index_super.php';
		$url5 = "index_ppc_super.php";
		if($ua['name'] == "Google Chrome")
	{
               	header ("Location: ".$data_setup["urls_system"]."/ppc_super/index_ppc_super.php?page=".encode($url5,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc_super/index_ppc_super.php?page=".encode($url5,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc_super/index_ppc_super.php");	
	}	
		}//display ppc super screen
  elseif ($info['level_id']== 6)
		{
		// session 'index_supply.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url6 = "index_supply.php";
		if($ua['name'] == "Google Chrome")
	{
                header ("Location: ".$data_setup["urls_system"]."/supply/index_supply.php?page=".encode($url6,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/supply/index_supply.php?page=".encode($url6,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/supply/index_supply.php");	
	}	
		}//display wip supply screen
		 elseif ($info['level_id']== 7)
		{
		// session 'index_supply.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url7 = "index_supply_super.php";
		if($ua['name'] == "Google Chrome")
	{
               header ("Location: ".$data_setup["urls_system"]."/supply_super/index_supply_super.php?page=".encode($url7,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/supply_super/index_supply_super.php?page=".encode($url7,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/supply_super/index_supply_super.php");	
	}	
	
		}//display wip supply superuser screen
		elseif ($info['level_id']== 8)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
include 'backjob_clean.php';
	
		$url8 = "index_planning.php";
		if($ua['name'] == "Google Chrome")
	{
                 header ("Location: ".$data_setup["urls_system"]."/planning/index_planning.php?page=".encode($url8,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/planning/index_planning.php?page=".encode($url8,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/planning/index_planning.php");	
	}	
		}//display planning dept
    elseif ($info['level_id']== 9)
		{
		// session 'index_super.php
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
include 'backjob_clean.php';	
		$url9 = "index_planning_super.php";
		if($ua['name'] == "Google Chrome")
	{
		header ("Location: ".$data_setup["urls_system"]."/planning_super/index_planning_super.php?page=".encode($url9,12));	

		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/planning_super/index_planning_super.php?page=".encode($url9,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/planning_super/index_planning_super.php");	
	}	
		}//display planning super 
   elseif ($info['level_id']== 10)
		{
		
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url10 = "index_qqc.php";
		if($ua['name'] == "Google Chrome")
	{        header ("Location: ".$data_setup["urls_system"]."/qqc/index_qqc.php?page=".encode($url10,12));	
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/qqc/index_qqc.php?page=".encode($url10,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/qqc/index_qqc.php");	
	}	
		}//display QA/QC dept
	 elseif ($info['level_id']== 11)
		{
		
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url11 = "index_qqc_super.php";
		if($ua['name'] == "Google Chrome")
	{         header ("Location: ".$data_setup["urls_system"]."/qqc_super/index_qqc_super.php?page=".encode($url11,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/qqc_super/index_qqc_super.php?page=".encode($url11,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/qqc_super/index_qqc_super.php");	
	}	
		}//display QA/QC superadmin dept	
		 elseif ($info['level_id']== 12)
		{
		
$username = $_POST["username"]; 
$password = $_POST["pass"];
session_start();
 $_SESSION["username"] = $username;
$_SESSION["password"] = $password;
	
		$url12 = "index_ppc_store.php";
		if($ua['name'] == "Google Chrome")
	{	
                header ("Location: ".$data_setup["urls_system"]."/ppc_store/index_ppc_store.php?page=".encode($url12,12));
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc_store/index_ppc_store.php?page=".encode($url12,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc_store/index_ppc_store.php");	
	}	
		}//display ppc store	
 } 
 } 
 } 
 else 
{	 
 
 // if they are not logged in 
 ?>
        <div id="loginbox">            
           <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginform" class="form-vertical" >
				 <div class="control-group normal_text"> 
           
				   <font size="5" color="#FFFFFF">Production Support System Online</font>
				 </div>
                <!--<div class="control-group">-->
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lg"><i class="icon-user"> </i></span><input name="username" type="text" placeholder="Username" />
                        </div>
                    </div>
               <!-- </div>-->
                <!--<div class="control-group">-->
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span><input name="pass" type="password" placeholder="Password" />
                        </div>
                    </div>
                <!--</div>-->
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Forgot password?</a></span>
                    <span class="pull-right"><input name="submit" type="submit" value="LOGIN" class="btn btn-success"/></span>
                </div>
            </form>
  <form id="recoverform" action="forgot_password.php" class="form-vertical" data-remote="true" method="post">
				<p class="normal_text">Enter your username and e-mail address below.</p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lo"><i class="icon-user"></i></span> <input type="text" name="user_name" size="20"  id="user_name" value="<?php if(isset($_POST['user_name'])) echo $_POST['user_name']; ?>">
                        </div>
                    </div>
                   
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lo"><i class="icon-envelope"></i></span> 
                       <input type="text" name="email"  size="50" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                </div>
                    </div>
               <div class="form-actions">
                   <span class="pull-left"><a href="index.php" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
               <span class="pull-right"><input name="submit2" type="submit" class="btn btn-info"  value="ENTER" ></span>
               </div>
            </form>
        </div>
       <?php 
 } 

 ?>  
<script src="js/jquery.min.js"></script>  
<script src="js/matrix.login.js"></script> 
<script src="js/excanvas.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.flot.min.js"></script> 
<script src="js/jquery.flot.resize.min.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/fullcalendar.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.dashboard.js"></script> 
<script src="js/jquery.gritter.min.js"></script> 

    </body>

</html>
