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
   
    $firstname=$_POST['Firstname'];
	$lastname=$_POST['Lastname'];
	$Designation=$_POST['Designation'];
	$OfficeNo=$_POST['OfficeNo'];
	$ResNo=$_POST['ResNo'];
	$AddeDate=date("m/d/Y");
    $email=$_POST['Email'];

   $query="insert into officers (FirstName,LastName,Designation,OfficeNo,ResidenceNo,Email,AddedDate) values('$firstname','$lastname','$Designation','$OfficeNo','$ResNo','$email','$AddeDate')";

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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_officer_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();
 


?>

