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

if($_POST['submit']!='')
 {

   Editletter($db);
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'letters.php'
        //-->
        </script>";  
 }

  $lid=$_GET['lid'];
  Letter($db,$lid);


 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_letter.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


function Editletter($db)
{
  $daak_id=$_POST['daak_id'];
  $paatalname=$_POST['paatalname'];
  $clerkname=$_POST['clerkname'];
  $letterno=$_POST['letterno'];
  $l_date=$_POST['l_date'];
  $l_date=explode("/",$l_date);
  $ldate=mktime(0,0,0,$l_date[1],$l_date[0],$l_date[2]);
  $l_from=$_POST['l_from'];
  $l_to=$_POST['l_to'];
  $markedto=$_POST['markedto'];
  $l_status=$_POST['l_status'];
  $l_action=$_POST['l_action'];
  $l_answer=$_POST['l_answer'];
  $l_subject=$_POST['l_subject'];
  $lid=$_POST['lid'];
      
  $update="update tbl_letters set daak_id='$daak_id',paatalname='$paatalname',clerkname='$clerkname',letterno='$letterno',l_date='$ldate',l_from='$l_from',
           l_to='$l_to',markedto='$markedto',l_status='$l_status',l_action='$l_action',l_answer='$l_answer',l_subject='$l_subject' where l_id='$lid'";

  $db->query($update);   
 
}


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
