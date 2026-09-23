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
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$id=$_GET['aid'];
$type=$_GET['type'];
$message="";
if($_POST['add']!='')
 {
   AddComment($db);   
   $message="Comment add successfully";
 }


$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_comment.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


 
function AddComment($db)
 { 
    $comment=$_POST['comment'];
    $reg_id=$_REQUEST['reg_id'];
    $type_id=$_REQUEST['type_id'];
    $insert1="insert into tbl_comment(reg_id,comment,type) values('$reg_id','$comment','$type_id')";
    $db->query($insert1);
 }
 
 
?>
