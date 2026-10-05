<?php session_start(); ?>

<?php

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
error_reporting(1);
if($_POST['username']!="")
{		
     $password = $_POST["password"];
     $username = $_POST["username"];
   
		if (authenticateUser($password, $username, $db))
	   {	
        echo "<script type='text/javascript'>
                <!--
                  window.location = 'welcome.php'
                //-->
                </script>
              ";
		 }
}
error_reporting(1);
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/index.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


function authenticateUser($password, $username, $db)
{	
	GLOBAL $PROMPT;
		
	$query="select * from admin where username = '$username'";	 
  $db->query($query);
	if ($db->num_rows())
	 {	
	   $password=md5($password);
		 $row = $db->fetch_array();
 		 $v_password = $row['password'];
			 
		if ($password==$v_password) // Binary Comparision for Case Sensitivity
		{	
		
			$username= $row['username'];
			$type=$row['type'];
			$_SESSION['type']=$type;
			$_SESSION['username']= $username;
			return true;
		}
		else
		{	//Not Authenticated
	    
			$PROMPT = "Authentication failed";
			$PROMPT_CLASS = "error";
			return false;
		}
			
	}
	else
	{	//Not Authenticated
 
		$PROMPT = "Authentication failed";
		$PROMPT_CLASS = "error";
		return false;
	}
}




?>