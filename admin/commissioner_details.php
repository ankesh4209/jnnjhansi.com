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

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_on";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$cid=$_GET['cid'];
$query="select * from municipal_comm where Comm_Id='$cid'";
$db->query($query);
$rows = $db->fetch_array();
if($_SERVER['SERVER_NAME']=='localhost')
  {
	  $c_image="<img src='/jnnweb/c_images/".$rows['Comm_Photo']."' width='302' height='177'>";
  }
  else
  {
		$c_image="<img src='/c_images/".$rows['Comm_Photo']."' width='302' height='177'>";
  }

$Comm_Name=$rows['Comm_Name'];
$Comm_Desc=$rows['Comm_Desc'];

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/commissioner_details.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


?>
