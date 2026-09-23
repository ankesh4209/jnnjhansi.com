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

   editofficer($db);
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'officer.php'
        //-->
        </script>";  
 }

$fid=$_GET['sfid'];
officerdetails($db,$fid);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_officer.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function editofficer($db)
{
   $ename=addslashes($_POST['offname']);
   $eaddress=addslashes($_POST['address']);
   $econtact=addslashes($_POST['contact']);
   $econtact1=$_POST['contact1'];
   $offcode=$_POST['offcode'];
   $offdesi=$_POST['offdesi'];
   $department=$_POST['department'];
   $email=$_POST['email'];
   
   $fid=$_REQUEST['sfid'];
   $update="update officer set officer_name='$ename',officer_add='$eaddress',off_code='$offcode',off_desi='$offdesi',off_department='$department',
            officer_contno='$econtact',off_contno1='$econtact1',email='$email' where o_id='$fid'";
   $db->query($update);
     
}


function officerdetails($db,$fid)
{
   global $fid,$fname,$sf_design,$email,$s_address,$fname,$faddress,$fcontact,$offcode,$offdesi,$department,$fcontact1;
   
   $sql="select * from officer where o_id='$fid'";
   $db->query($sql);
   $rows = $db->fetch_array();
   $fid=$rows['o_id'];
   $fname=stripslashes($rows['officer_name']);
   $faddress=stripslashes($rows['officer_add']);
   $fcontact=stripslashes($rows['officer_contno']);
   $offcode=$rows['off_code'];
   $offdesi=$rows['off_desi'];
   $department=$rows['off_department'];
   $fcontact1=$rows['off_contno1'];
   $email=$rows['email'];
}

?>
