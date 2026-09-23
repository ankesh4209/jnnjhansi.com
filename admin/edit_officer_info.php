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

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

 $oid=$_REQUEST['oid'];
   
  
GetOfficerInfo($db,$oid);


if($_POST["submit"]!='')
 {
    $firstname=$_POST['Firstname'];
	$lastname=$_POST['Lastname'];
	$Designation=$_POST['Designation'];
	$OfficeNo=$_POST['OfficeNo'];
	$ResNo=$_POST['ResNo'];
	$email=$_POST['Email'];
   
   $query="update officers set FirstName='$firstname',LastName='$lastname',Email='$email',Designation='$Designation',OfficeNo='$OfficeNo',ResidenceNo='$ResNo' where Officer_Id='$oid'";
   $db->query($query);
  
     echo "<script type='text/javascript'>
        <!-- 
         window.location = 'officers_info.php'
        //-->
        </script>";
 }

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_on";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_officer_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();
 

function GetOfficerInfo($db,$oid)
{
	global $email,$firstname,$lastname,$Designation,$OfficeNo,$ResNo,$oid;
	$query="select * from officers where Officer_Id='$oid'";
    $db->query($query);
    $rows = $db->fetch_array();
  
	
	$oid=$rows['Officer_Id'];
	$firstname=$rows['FirstName'];
	$lastname=$rows['LastName'];
	$Designation=$rows['Designation'];
	$OfficeNo=$rows['OfficeNo'];
	$ResNo=$rows['ResidenceNo'];
	$email=$rows['Email'];
}
?>

