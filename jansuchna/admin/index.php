<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!="")
{		
  $password = $_POST["password"];
   $username = $_POST["uname"];
   
		if (authenticateUser($password, $username, $db))
	   {	
        echo "<script type='text/javascript'>
                <!--
                  window.location = 'welcome.php'
                //-->
                </script>
              ";
		 }
		else
		 {
       echo "<script type='text/javascript'>
                <!--
                  window.location = '../login.php?uid=1'
                //-->
                </script>
             ";
     }
}

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
		
  $query="select * from tbl_admin where username = '$username'";	 
   $db->query($query);
	if ($db->num_rows())
	 {	
		 $row = $db->fetch_array();
	   $v_password = $row['password'];
			 
				if (strcmp ( md5($password), $v_password )==0 ) // Binary Comparision for Case Sensitivity
				{	
			  	$username= $row['username'];
					$_SESSION['user_name']= $username;
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