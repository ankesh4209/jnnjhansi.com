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

if($_POST['submit']!='')
 {
   addofficer($db);
   echo "<script type='text/javascript'>
    <!-- 
     window.location = 'con_officer.php'
    //-->
    </script>"; 
   
 }
 
GetSupervisory($db);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_conofficer.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function addofficer($db)
 {
     $sfname=addslashes($_POST['offname']);
     $s_id=$_POST['s_officer'];
     $saddress=addslashes($_POST['address']);
     $scontact=addslashes($_POST['contact']);
     $scontact1=addslashes($_POST['contact1']);
     $offcode=$_POST['offcode'];
     $offdesi=$_POST['offdesi'];
     $department=$_POST['department'];
     $email=$_POST['email'];

      $insert="insert into con_officer(off_code,s_id,off_desi,officer_name,officer_add,officer_contno,email) 
               values('$offcode','$s_id','$offdesi','$sfname','$saddress','$scontact','$email')";
      $db->query($insert);
  
 }

function GetSupervisory($db)
 {
   global $s_officerlist;
   $sql="select * from nature";
   $db->query($sql);
   while($row=$db->fetch_array())
    {
      $sid=$row['nature_id'];
      $sname=$row['nature_name'];
      $s_officerlist.="<option value='$sid'>$sname</option>";
    }
 }
?>