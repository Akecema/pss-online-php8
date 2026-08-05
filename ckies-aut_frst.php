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
 	 	$check = mysql_query("SELECT * FROM user_detail WHERE username = '$username' AND status = 'AC' AND status_failed = 'N'")or die(mysql_error());
 	while($info = mysql_fetch_array($check)) 	
 		{
 		if ($pass != $info['password']) 
 			{
				
	   //-------------additional for checking failed login 5 times ---------------
		//-------------edit date 16/11/2017
		
		$check_log = "SELECT * FROM failed_login AS FL, user_detail AS UL WHERE FL.staff_ID = UL.staff_ID AND FL.username = '".$_POST['username']."' AND FL.ip_address = '".$_SERVER["REMOTE_ADDR"]."'  AND FL.date_failed BETWEEN DATE_SUB(NOW() , INTERVAL 1 DAY) AND NOW()";
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
				
				
 			 			
 		}else{
 			
			
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
          header ("Location: ".$data_setup["urls_system"]."/admin/index_admin.php");
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

        header ("Location: ".$data_setup["urls_system"]."/prod/index_production.php");	

		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/prod/index_production.php".encode($url2,12));
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
		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ppc/index_ppc.php");
		
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
		header ("Location: ".$data_setup["urls_system"]."/ppc_super/index_ppc_super.php");	
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
		header ("Location: ".$data_setup["urls_system"]."/supply/index_supply.php");	
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
		header ("Location: ".$data_setup["urls_system"]."/supply_super/index_supply_super.php");
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
	{	header ("Location: ".$data_setup["urls_system"]."/planning/index_planning.php");
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
	{
		header ("Location: ".$data_setup["urls_system"]."/qqc/index_qqc.php?page=".encode($url10,12));	

		//header ("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/qqc/index_qqc.php");
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
		header ("Location: ".$data_setup["urls_system"]."/qqc_super/index_qqc_super.php");	
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
		else{ 
		
		 	if($ua['name'] == "Google Chrome")
	    {
		  header ("Location: ".$data_setup["urls_system"]."/blankPg.php");	
               //header("Location: https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/blankPg.php"); 
		
		
		}elseif(($ua['name'] == "Apple Safari") || ($ua['name'] == "Mozilla Firefox") || ($ua['name'] == "Internet Explorer"))
	    {
		
	    header ("Location: ".$data_setup["urls_system"]."/blankPg.php");	
		
	     }	
		
 			}// end else blankPg.php
 		
		} // end else level

} // while info loop
 
 }  // end $_COOKIE['ID_my_site']



?>