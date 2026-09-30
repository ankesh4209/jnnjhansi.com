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
    $MESSAGE = "<div class='admin-alert alert-success' style='background:#DEF7EC; color:#03543F; padding:12px 16px; border-radius:6px; font-weight:600; margin-bottom:15px; border:1px solid #BCF0DA;'><i class='fa fa-check-circle'></i> Password has been changed successfully!</div>";
 }
else
 {
    $MESSAGE = "";
 }

$uname = $_SESSION['user_name'] ?? 'admin';
$q = "SELECT last_changed FROM login WHERE username='$uname' OR l_id='1' LIMIT 1";
$db->query($q);
$row = $db->fetch_assoc();
$last_changed_val = $row['last_changed'] ?? '';
if (!empty($last_changed_val)) {
    $ts = strtotime(str_replace('/', '-', $last_changed_val));
    $LAST_CHANGED_DISPLAY = $ts ? date('d-M-Y h:i A', $ts) : htmlspecialchars($last_changed_val);
} else {
    $LAST_CHANGED_DISPLAY = "Not Recorded Yet";
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

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "MESSAGE", "LAST_CHANGED_DISPLAY"));
print $TEMPLATE;
flush();

function changepassword($db)
{	
   global $db, $MESSAGE;

   $password = md5($_POST['password']);
   $nowStr = date('d-m-Y H:i:s');
   $uname = $_SESSION['user_name'] ?? 'admin';
   $query = "update login set password ='$password', last_changed='$nowStr' where username ='$uname' or l_id ='1'";
   $db->query($query);
   @$db->query("update admin set password ='$password', last_changed='$nowStr' where username ='$uname' or id ='1'");
}


?>
