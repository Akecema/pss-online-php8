<?php


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

 	
 	$check = mysql_query("SELECT * FROM user_detail WHERE username = '".$_POST['username']."' AND status = 'AC' AND status_failed = 'N'")or die(mysql_error());

 //Gives error if user dosen't exist
 $check2 = mysql_num_rows($check);
 if ($check2 == 0) {

 
                     echo "<br><br>"; 
		             echo "<script>";
			         echo "alert('Access denied. Kindly contact System Administrator.');";
		             echo "window.location='index.php'";
					 echo "</script>";
 
 
 		//die('That user does not exist in our database. Please contact IAV Administrator to Register.');
 				}
 while($info = mysql_fetch_array( $check )) 	
 {
    $_POST['pass'] = stripslashes($_POST['pass']);
 	$info['password'] = stripslashes($info['password']);
 	$_POST['pass'] = md5($_POST['pass']);

 //gives error if the password is wrong
 	if ($_POST['pass'] != $info['password']) {
	
	
		
		//-------------additional for checking failed login 5 times ---------------
		//-------------edit date 16/11/2017
		
		$check_log = "SELECT * FROM failed_login AS FL, user_detail AS UL WHERE FL.staff_ID = UL.staff_ID AND FL.username = '".$_POST['username']."' AND FL.ip_address = '".$_SERVER["REMOTE_ADDR"]."'  AND FL.date_failed BETWEEN DATE_SUB( NOW() , INTERVAL 1 DAY ) AND NOW()";
		$rs_check_log = mysql_query($check_log);   
	    $num_check_log = mysql_num_rows($rs_check_log);  
		$row = mysql_fetch_array($rs_check_log);
		
		if($num_check_log < 2)
		{
			
				
		$query_log = "INSERT INTO failed_login(ip_address,date_failed,staff_ID,username) VALUES('".$_SERVER["REMOTE_ADDR"]."',NOW(),'".$info['staff_ID']."','".$_POST['username']."')";
		$result_log = mysql_query($query_log) or die (mysql_error());
		
		             
		             echo "<script>";
			         echo "alert('Incorrect password, please try again.');";
		             echo "window.location='index.php'";
					 echo "</script>";
			
		}else{
		
		//------update status_failed -------------------
		//------hantar e-mail kpd administrator---------
		
		//----------------------------------------------	
			      include "rst-mail.php";
		
		
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
		header ("Location: ".$data_setup["urls_system"]."/admin/index_admin.php");
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

                 header ("Location: ".$data_setup["urls_system"]."/prod/index_production.php");	

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
                header ("Location: ".$data_setup["urls_system"]."/prod_super/index_production_super.php");	
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
               header ("Location: ".$data_setup["urls_system"]."/ppc/index_ppc.php");	

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
               	header ("Location: ".$data_setup["urls_system"]."/ppc_super/index_ppc_super.php");
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
                header ("Location: ".$data_setup["urls_system"]."/supply/index_supply.php");	
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
               header ("Location: ".$data_setup["urls_system"]."/supply_super/index_supply_super.php");	
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
                 header ("Location: ".$data_setup["urls_system"]."/planning/index_planning.php");
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
		header ("Location: ".$data_setup["urls_system"]."/planning_super/index_planning_super.php");	

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
	{        header ("Location: ".$data_setup["urls_system"]."/qqc/index_qqc.php");	
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
	{         header ("Location: ".$data_setup["urls_system"]."/qqc_super/index_qqc_super.php");
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
                header ("Location: ".$data_setup["urls_system"]."/ppc_store/index_ppc_store.php");
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc_store/index_ppc_store.php?page=".encode($url12,12));
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	{
		header ("Location: ".$data_setup["urls_system"]."/ppc_store/index_ppc_store.php");	
	}	
		}//display ppc store	
 
 }  // end else post pass x sama
 
 } // end while  
 
 ?>

