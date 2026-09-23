<?php	
session_start();
include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!='') {
  $mobile=$_POST['mobile'];
  $password=$_POST['password'];
  
  $select="select * from smartcity_reg where mobile='$mobile' AND password='$password'";
  $res=$db->query($select);
  if($db->num_rows()) {
    $res=$db->fetch_array($row);
    $_SESSION['user_id']=$res['id'];
     echo"<script type='text/javascript'>
        <!-- 
         window.location = 'smartcity_feedback.php'
        //-->
        </script>"; 
  } else {
   $msg="Please enter correct login details.";
  }
}

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/smartcity_login.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


?>
