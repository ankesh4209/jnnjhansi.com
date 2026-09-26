<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if(isset($_POST['submit']) && $_POST['submit']!="")
{		
    $password = $_POST["password"];
    $username = $_POST["uname"];
    
    if (authenticateUser($password, $username, $db))
    {	
        if($_SESSION['type']==1) {
            header("Location: welcome.php");
            echo "<script type='text/javascript'>window.location = 'welcome.php';</script>";
            exit;
        } else {
            header("Location: smartcity_campagin.php");
            echo "<script type='text/javascript'>window.location = 'smartcity_campagin.php';</script>";
            exit;
        } 
    }
}

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/index.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR         = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "PROMPT"));
print $TEMPLATE;
flush();


function authenticateUser($password, $username, $db)
{	
	GLOBAL $PROMPT;
		
  $query="select * from login where username = '$username'";	 
   $db->query($query);
	if ($db->num_rows())
	 {	
		 $row = $db->fetch_array();
		   $v_password = $row['password'];
			 
				if (strcmp ( md5($password), $v_password )==0 ) // Binary Comparision for Case Sensitivity
				{	
			  	$username= $row['username'];
			  	$type = $row['type'];
					$_SESSION['user_name']= $username;
					$_SESSION['type']= $type;
					return true;
				}
				else
				{	//Not Authenticated
					$PROMPT = "<div class='login-error-alert'>⚠️ Invalid username or password. Please try again.</div>";
					$PROMPT_CLASS = "error";
					return false;
				}
			
	}
	else
	{	//Not Authenticated
		$PROMPT = "<div class='login-error-alert'>⚠️ Invalid username or password. Please try again.</div>";
		$PROMPT_CLASS = "error";
		return false;
	}
}




?>