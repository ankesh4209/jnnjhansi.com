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
   addcorporator($db);
   echo "<script type='text/javascript'>
    <!-- 
     window.location = 'corporator.php'
    //-->
    </script>"; 
   
 }
 
GetWardNo($db);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_corporator.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function addcorporator($db)
 {
     $wardno=addslashes($_POST['wardno']);
     $name=$_POST['name'];
     $address=addslashes($_POST['address']);
     
      $insert="insert into corporators(CorporatorName,WardNo,Address) 
               values('$name','$wardno','$address')";
      $db->query($insert);
  
 }

function GetWardNo($db)
 {
   global $wardnolist;
   $sql="select * from ward order by WardNo ASC";
   $db->query($sql);
   while($row=$db->fetch_array())
    {
      $WardNo=$row['WardNo'];
      $wardnolist.="<option value='$WardNo'>$WardNo</option>";
    }
 }
?>