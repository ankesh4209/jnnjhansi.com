<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
include("../phplib/thumbclass.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

if($_POST['submit']!="")
{
		  extract($_POST);
		  $CategoryName=addslashes($CategoryName);
		  $CategoryId=$CategoryId;

		  $update="update epr_itemcategory set 
			CategoryName='".$CategoryName."'
			where CategoryId='$CategoryId'"; 
			
						  
				  $db->query($update);

		  
		     
	
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'category.php?msg=e_succ'
      //-->
      </script>"; 

}


$id=$_GET['cid'];
GetCategoryDetails($db,$id);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_category.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetCategoryDetails($db,$id)
 {
    global $CategoryId,$CategoryName;
    
    $sql="select * from epr_itemcategory where CategoryId='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
		  $CategoryName=stripslashes($rows['CategoryName']);
		  $CategoryId=stripslashes($rows['CategoryId']);
 }


?>
