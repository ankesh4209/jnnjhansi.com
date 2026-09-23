<?php session_start(); ?>
<?php 
 if ($_SESSION['username']=='')
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

 editward($db);
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'wards.php'
        //-->
        </script>";  
 }

  $wid=$_GET['wid'];
 wardDetails($db,$wid);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_ward.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function editward($db)
{

     $wardno=addslashes($_POST['wardno']);
     
     
    
     $wid=$_REQUEST['wid'];
     $update="update ward set WardNo='$wardno' where WardId='$wid'";
     $db->query($update);
     
 
}








function  wardDetails($db,$wid)
{
global $wardno,$wid;
   $sql="select * from ward where WardId='$wid'";
   $db->query($sql);
   $rows = $db->fetch_array();
   $wid=$rows['WardId'];
   $wardno=stripslashes($rows['WardNo']);
    
  
}

?>
