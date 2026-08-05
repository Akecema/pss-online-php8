<?php                 
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

?>