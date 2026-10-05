<?php session_start(); ?>
<?php 
 if ($_SESSION['username']=='')
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

if($_POST['cpassword']!="")
{		
     $cpassword = $_POST["cpassword"];
     $newpassword = $_POST["newpassword"];
     authenticateUser($cpassword, $newpassword, $db);
	 
}

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/change.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function authenticateUser($cpassword, $newpassword, $db)
{	
 GLOBAL $PROMPT;
 $newpassword=md5($newpassword);
 $cpassword=md5($cpassword);
 $update="update admin set password='$newpassword'  where  password='$cpassword'";
 $db->query($update);
 $PROMPT = "Password has been changed";

}

?>
