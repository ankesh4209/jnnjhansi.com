<?php session_start(); ?>
<?php	

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$lid=$_GET['lid'];
Letter($db,$lid);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/print_letter.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();

function  Letter($db,$lid)
{
  global $lid,$daak_id,$paatalname,$clerkname,$letterno,$l_date,$l_from,$l_to,$markedto,$l_status,$l_action,$l_answer,$l_subject;
  
   $sql="select * from tbl_letters where l_id='$lid'";
   $db->query($sql);
   $rows = $db->fetch_array();
   $lid=$rows['l_id'];
   $daak_id=$rows['daak_id'];
   $paatalname=$rows['paatalname'];
   $clerkname=$rows['clerkname'];
   $letterno=$rows['letterno'];
   $l_date=$rows['l_date'];
   $l_date=date('d/m/Y',$l_date);
   $l_from=$rows['l_from'];
   $l_to=$rows['l_to'];
   $markedto=$rows['markedto'];
   $l_status=$rows['l_status'];
   $l_action=$rows['l_action'];
   $l_answer=$rows['l_answer'];
   $l_subject=$rows['l_subject'];
}

 
 
?>
