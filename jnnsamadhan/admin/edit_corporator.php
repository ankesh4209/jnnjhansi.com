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

   editcorporator($db);
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'corporator.php'
        //-->
        </script>";  
 }

$cid=$_GET['scid'];
$wid=$_GET['wid'];
Corporatordetails($db,$cid);
GetWardNo($db,$wid);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
  
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_corporator.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function editcorporator($db)
{
   $wardno=addslashes($_POST['wardno']);
   $name=$_POST['name'];
   $eaddress=addslashes($_POST['address']);
   
   $cid=$_REQUEST['scid'];
   $update="update corporators set CorporatorName='$name',WardNo='$wardno',Address='$eaddress' where CorporatorId='$cid'";
   $db->query($update);
     
}


function Corporatordetails($db,$cid)
{
   global $cid,$name,$address,$WardNo;
   
   $sql="select * from corporators where CorporatorId='$cid'";
   $db->query($sql);
   $rows = $db->fetch_array();
   $cid=$rows['CorporatorId'];
   $name=stripslashes($rows['CorporatorName']);
   $WardNo=stripslashes($rows['WardNo']);
   $address=stripslashes($rows['Address']);
  
}

function GetWardNo($db,$wid)
 {
   global $wardnolist;
   $sql="select * from ward order by WardNo ASC";
   $db->query($sql);
   while($row=$db->fetch_array())
    {
      $WardNo=$row['WardNo'];
      if($WardNo==$wid)
      $wardnolist.="<option value='$WardNo' selected>$WardNo</option>";
      else
	  $wardnolist.="<option value='$WardNo'>$WardNo</option>";
    }
 }

?>
