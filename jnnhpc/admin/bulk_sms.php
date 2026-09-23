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

error_reporting(0);

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

if($_POST['submit']!="")
 {
    $tomessage=$_POST['message1'];
    $tomessage=str_replace(" ","%20",$tomessage);
    $group=$_POST['group'];
    
     $sql="select * from group_member where group_id='$group'";
     $res=$db->query($sql);
     while($rows=$db->fetch_array())
     {
       $member=$rows['member_id'];
       $type=$rows['type_id'];
       if($type==1)
        {
          $sql1="select * from officer where  o_id='$member'";
        }
       else
        { 
          $sql1="select * from con_officer where  c_id='$member'";
        }
        
       $db1->query($sql1);
       $rows1=$db1->fetch_array();
       $contact_no=$rows1['officer_contno'];
       
       $url3="http://websms.one97.net/sendsms/push_sms.php?user=anoopjhoshi&pwd=anoopjhoshi&from=UPHDB&to=$contact_no&msg=$tomessage";
       $file3=fopen("$url3","r");
       fclose("$file3");
     }
     
    $PROPMT="Message Sent Successfully";
 }

GetGroup($db);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/bulk_sms.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function GetGroup($db)
 {
   global $groupname;
  
    $sql="select * from group_name";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $gid = $res['group_id'];
         $gname = $res['group_name'];
         $groupname.="<option value='$gid'>$gname</option>";
       }
    }
 
 }
?>