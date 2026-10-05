<?php 
session_start();	
 if ($_SESSION['ContractorId']=='')
  {	
    header ("Location: contractor_login.php"); 				
	  exit;
  }
include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


if($_POST['changepass_x']!=''){
      change_password($db);
}

//print_r($_POST);
//die;


$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/change_password.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/contra_template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/contra_bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/contra_topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();


function change_password($db){
	global $PROMPT,$db; 
	extract($_POST);
	$sql="select password from contractors where ContractorId='".$_SESSION['ContractorId']."'";
	$res=$db->query($sql);
	$rows=$db->fetch_array();
	$pass=$rows['password'];
	if($pass==$oldpassword){
		$update_sql="update contractors set password='".$newpassword."' where ContractorId='".$_SESSION['ContractorId']."'";
        $db->query($update_sql);
		$PROMPT='Password has been changed successfully.';
	}else{
	  $PROMPT='Old password do not match.';
	}
}
?>
