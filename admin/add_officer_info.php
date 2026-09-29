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




if($_POST["submit"]!='')
 {
    $firstname   = $db->escape_string($_POST['Firstname'] ?? $_POST['firstname'] ?? $_POST['fname'] ?? '');
	$lastname    = $db->escape_string($_POST['Lastname'] ?? $_POST['lastname'] ?? $_POST['lname'] ?? '');
	$Designation = $db->escape_string($_POST['Designation'] ?? $_POST['designation'] ?? '');
	$OfficeNo    = $db->escape_string($_POST['OfficeNo'] ?? $_POST['office_no'] ?? '');
	$ResNo       = $db->escape_string($_POST['ResNo'] ?? $_POST['ResidenceNo'] ?? $_POST['residence_no'] ?? '');
	$AddeDate    = date("m/d/Y");
    $email       = $db->escape_string($_POST['Email'] ?? $_POST['email'] ?? '');
    $status      = isset($_POST['status']) ? (int)$_POST['status'] : 1;

   $query="insert into officers (FirstName,LastName,Designation,OfficeNo,ResidenceNo,Email,Status,AddedDate) values('$firstname','$lastname','$Designation','$OfficeNo','$ResNo','$email','$status','$AddeDate')";

     $db->query($query);
  
     echo "<script type='text/javascript'>
        <!-- 
         window.location = 'officers_info.php';
        //-->
        </script>";
     exit;
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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_officer_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();
 


?>

