<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!='') {
  $mobile=$_POST['mobile'];
  $name=$_POST['name'];
  $address=$_POST['address'];
  $password=rand(00000,99999);
  
  $insert="insert into smartcity_reg (name,mobile,password,address) values ('$name','$mobile','$password','$address')";
  $res=$db->query($insert);
  
  if($mobile!='') {
    //--- send sms ------
    $message1="login%20details%20for%20smart%20city.%20username%20$mobile%20password%20$password";
    $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=JNNJHS&password=JNNJHS&mobiles=$mobile&sms=$message1&senderid=JNNJHS";
    $file1=fopen("$url1","r");
    fclose("$file1");
  }
  
  $msg="Thank you for registration. Your login details has been sent to your mobile.";
  
}

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/smart_city.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


?>
