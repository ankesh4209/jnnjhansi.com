<?php session_start(); 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: ../login.php"); 				
	  exit;
  }

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
include("../phplib/thumbclass.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

if($_POST['submit']!="")
{
		  extract($_POST);
		  $ItemName=addslashes($ItemName);
		  $Unit=$Unit;
		  $Rate=$Rate;
		  
		  $insert="insert into cw_ratelist (ItemName,Unit,Rate) values ('".$ItemName."','".$Unit."','".$Rate."')"; 
			
						  
				  $db->query($insert);

		  
		     
	
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'ratelist.php?msg=e_succ'
      //-->
      </script>"; 

}


 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_item.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();



?>
