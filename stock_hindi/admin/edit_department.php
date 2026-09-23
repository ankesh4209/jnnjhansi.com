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

$id=$_GET['id'];
GetNature($db,$id);

if($_POST['submit']!='')
 {
   $cid=$_REQUEST['cid'];
   editNature($db,$cid);
   
   echo "<script type='text/javascript'>
    <!-- 
     window.location = 'departments.php'
    //-->
    </script>"; 
   
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_department.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function editNature($db,$cid)
 {
    $cname=addslashes($_POST['nname']);
    $update="update department set DepName='$cname' where DeptId='$cid'";
    $db->query($update);
 }

function GetNature($db,$id)
 {
   global $name;
   $sql="select DeptName from department where DeptId='$id'";
   $res=$db->query($sql);
   $rows=$db->fetch_array($res);
   $name=$rows['DepName'];
 }
?>