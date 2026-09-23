<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: index.php"); 				
	  exit;
  }
?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$class1="current";
$class2="";
$class3="";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/welcome.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
//$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");

ReplaceContent(Array("TOPBAR",  "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


?>
