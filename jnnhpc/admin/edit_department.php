<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: index.php"); 				
	  exit;
  }
?>
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

 editdepartment($db);
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'department.php'
        //-->
        </script>";  
 }

  $did=$_GET['did'];
 departmentsdetails($db,$did);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_department.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function editdepartment($db)
{

     $dname=addslashes($_POST['ename']);
     
     
    
     $did=$_REQUEST['did'];
     $update="update department set d_name='$dname' where d_id='$did'";
     $db->query($update);
     
 
}








function  departmentsdetails($db,$did)
{
global $fid,$fname,$sf_design,$sf_pay,$sf_adsalary,$s_address,$fname,$faddress,$fcontact,$dname,$did;
   $sql="select * from department where d_id='$did'";
   $db->query($sql);
   $rows = $db->fetch_array();
   $did=$rows['d_id'];
   $dname=stripslashes($rows['d_name']);
    
  
}

?>
