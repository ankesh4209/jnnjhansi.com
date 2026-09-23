<?php session_start(); ?>


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
         window.location = 'page.php'
        //-->
        </script>";  
 }

  $did=$_GET['did'];
 Department($db,$did);


 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_architect.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


function editdepartment($db)
{
    $dname=addslashes($_POST['dname']);
    $off_des=addslashes($_POST['off_des']);
    $off_name=addslashes($_POST['off_name']);
    $contact=$_POST['contact'];
    $soffcontact=$_POST['soff_contact'];
    $did=$_REQUEST['did'];
    echo '<pre>';
	//print_r($_POST);die;
    $update="update tbl_department set d_name='$dname',d_off_name='$off_name',d_off_designation='$off_des'
            ,d_off_contact='$contact',d_soff_contact='$soffcontact' where d_id='$did'";
    $db->query($update);  
}

function  Department($db,$did)
{
  global $did,$dname,$off_name,$off_des,$contact,$soffcontact;
   $sql="select * from tbl_department where d_id='$did'";
   $db->query($sql);
   $rows = $db->fetch_array();
   $did=$rows['d_id'];
   $dname=stripslashes($rows['d_name']);
   $off_name=stripslashes($rows['d_off_name']);
   $off_des=stripslashes($rows['d_off_designation']);
   $contact=$rows['d_off_contact'];
   $soffcontact=$rows['d_soff_contact'];
}

?>
