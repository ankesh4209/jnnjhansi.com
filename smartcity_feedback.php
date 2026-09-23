<?php	
session_start();
include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";
if($_SESSION['user_id']=='') {
  echo"<script type='text/javascript'>
      <!-- 
       window.location = 'smartcity_login.php'
      //-->
      </script>"; 
}

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!='') {
  $user_id=$_SESSION['user_id'];
  $answer1=$_POST['answer1'];
  $answer2=$_POST['answer2'];
  $answer3=$_POST['answer3'];
  $answer4=$_POST['answer4'];
  $answer5=$_POST['answer5'];
  $answer6=$_POST['answer6'];
  $answer7=$_POST['answer7'];
  $answer8=$_POST['answer8'];
  $answer9=$_POST['answer9'];
  $answer10=$_POST['answer10'];
  $answer11=$_POST['answer11'];
  $answer12=$_POST['answer12'];
  $answer13=$_POST['answer13'];
  $answer14=$_POST['answer14'];
  
  $insert="insert into smartcity_comment (user_id,comment1,comment2,comment3,comment4,comment5,comment6,comment7,comment8,comment9,comment10,
  comment11,comment12,comment13,comment14) values ('$user_id','$answer1','$answer2','$answer3','$answer4','$answer5','$answer6','$answer7','$answer8'
  ,'$answer9','$answer10','$answer11','$answer12','$answer13','$answer14')";
  $res=$db->query($insert);
  
  $msg="Thank you for your suggestion";
  
}

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/smartcity_feedback.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


?>
