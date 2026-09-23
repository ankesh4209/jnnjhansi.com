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

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!='')
 {
      Addletter($db);
       echo "<script type='text/javascript'>
        <!-- 
         window.location = 'letters.php'
        //-->
        </script>"; 
   
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_letter.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function Addletter($db)
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
  
  $insert="insert into tbl_letters(daak_id,paatalname,clerkname,letterno,l_date,l_from,l_to,markedto,l_status,l_action,l_answer,l_subject) 
           values('$daak_id','$paatalname','$clerkname','$letterno','$ldate','$l_from','$l_to','$markedto','$l_status','$l_action','$l_answer','$l_subject')";
  $db->query($insert);
 }
  
 
?>