<?php                 
                  
/**
 * rst-mail.php
 * Part of: Core / entry-point script
 * Filename suggests: rst mail
 *
 * Behavior: processes submitted form data ($_POST); sends email.
 * Database tables referenced: user_detail.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
// FIXED (2026-08-17): $_POST["username"] and $row["username"] used to be
// concatenated directly into SQL here - SQL-injectable, and reachable by an
// unauthenticated visitor (included via ckies-aut_frst.php/ckies-aut_scd.php
// after 5 failed logins in 24h). Now parameterised. Locks the account
// (status_failed='Y') and emails the user's registered address to notify them.
$stmt_update_fail = mysqli_prepare($dbc, "UPDATE user_detail SET status_failed = 'Y', date_failed = NOW(), user_update = ?, date_update = NOW() where username = ?") or die(db_fail($dbc));
					  mysqli_stmt_bind_param($stmt_update_fail, "ss", $row["username"], $_POST["username"]);
					  $result_update_fail = mysqli_stmt_execute($stmt_update_fail) or die(db_fail($dbc));
				  
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

?>