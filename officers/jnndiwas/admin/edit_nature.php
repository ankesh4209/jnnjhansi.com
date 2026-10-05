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
     window.location = 'nature_comp.php'
    //-->
    </script>"; 
   
 }

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_nature.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function editNature($db,$cid)
 {
    $cname=addslashes($_POST['nname']);
    $update="update nature set nature_name='$cname' where nature_id='$cid'";
    $db->query($update);
 }

function GetNature($db,$id)
 {
   global $name;
   $sql="select nature_name from nature where nature_id='$id'";
   $res=$db->query($sql);
   $rows=$db->fetch_array($res);
   $name=$rows['nature_name'];
 }
?>