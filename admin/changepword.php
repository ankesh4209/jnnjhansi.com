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

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if(isset($_POST["submit"]) && $_POST["submit"]!='')
 {
    changepassword($db);
    $MESSAGE = "<div class='admin-alert alert-success'>✅ Password has been changed successfully!</div>";
 }
else
 {
    $MESSAGE = "";
 }

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_on";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/changepword.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "MESSAGE"));
print $TEMPLATE;
flush();

function changepassword($db)
{	
   global $db,$MESSAGE;

  $password=md5($_POST['password']);
	$query="update login set password ='$password' where l_id ='1'";
	$db->query($query);
	
}


?>
