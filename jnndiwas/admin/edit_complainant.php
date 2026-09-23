<?php session_start(); ?>


<?php

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");
error_reporting(1);
$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());


if($_POST['submit']!='')
 {

  editcomplainant($db);
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'complainant.php'
        //-->
        </script>";  
 }

$cid=$_GET['cid'];
complainantdetails($db,$cid,$db1);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_complainant.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function editcomplainant($db)
{
    $cid=$_REQUEST['cid'];
    $cname=addslashes($_POST['cname']);
    $caddress=addslashes($_POST['address']);
    $ccontact=addslashes($_POST['contact']);
    $regno=addslashes($_POST['regno']);
    $cofficer=addslashes($_POST['officer']);
    $cdate=addslashes($_POST['cdate']);
    $ndate=$_POST['ndate'];
    $cmode=$_POST['cmode'];
    $newspapername=$_POST['newspname'];
    $summary=addslashes($_POST['summary']);
    $oldcomplain=$_POST['oldcno'];
    $comtime=$_POST['ctime'];
    $ccategory=addslashes($_POST['category']);
    $remark=addslashes($_POST['remark']);
    $tdate=$_POST['tdate'];
     
    $update="update complainant set c_name='$cname',c_add='$caddress',c_contno='$ccontact',c_regno='$regno',c_detail='$summary',
             officer_name='$cofficer',c_date='$cdate',c_category='$ccategory',c_remark='$remark',oldc_no='$oldcomplain',
             c_time='$comtime',c_newspaper='$newspapername',c_ndate='$ndate',c_mode='$cmode',tdate='$tdate' where c_id='$cid'"; 
    $db->query($update);
     
 
}

function complainantdetails($db,$cid,$db1)
{
   global $cid,$cname,$address,$contact,$regno,$ctime,$summary,$officer,$cdate,$category,$cmode,$ndate,$newspname,$oldcno,
          $select1,$select2,$select3,$select4,$select5,$remark,$more_officer,$tdate;
          
     $sql="select * from complainant where c_id='$cid'";
     $db->query($sql);
     $rows = $db->fetch_array();
     $cid=$rows['c_id'];
     $cname=stripslashes($rows['c_name']);
     $address=stripslashes($rows['c_add']);
     $contact=stripslashes($rows['c_contno']);
     $regno=stripslashes($rows['c_regno']);
     $summary=stripslashes($rows['c_detail']);
     $cdate=stripslashes($rows['c_date']);
     $remark=stripslashes($rows['c_remark']);
     $oldcno=$rows['oldc_no'];
     $ctime=$rows['c_time'];
     $newspname=$rows['c_newspaper'];
     $ndate=$rows['c_ndate'];
     $tdate=$rows['tdate'];
     
     //------------------------officer-----------------------
      $officer=stripslashes($rows['officer_name']);
      $sql1="select * from officer";
      $res1=$db1->query($sql1);
      while($rows1=$db1->fetch_array())
       {
         $cid1 = $rows1['o_id'];
         $officer1 = $rows1['officer_name'];
         if($cid1==$officer)
         $more_officer.="<option value='$cid1' selected>$officer1</option>";
         else
         $more_officer.="<option value='$cid1'>$officer1</option>";
       }
   
   //------------------------------C mode------------------------
     $cmode=$rows['c_mode'];
     if($cmode=='Newspaper')
      $select4="selected";
     else
      $select4="";
     
     if($cmode=='Fax')
      $select5="selected";
     else
      $select5="";
     
   //---------------------------------category-------------------
   
   $category=stripslashes($rows['c_category']);
   if($category=='A')
     $select1="selected";
   else
     $select1="";
     
   if($category=='B')
     $select2="selected";
   else
     $select2="";
     
   if($category=='C')
     $select3="selected";
   else
     $select3="";
}


?>
