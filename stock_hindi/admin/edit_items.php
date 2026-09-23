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
		  $ItemName=addslashes($ItemName);
		  $ItemType=addslashes($ItemType);

		  $update="update epr_items set 
			ItemName='".$ItemName."',
			ItemType='".$ItemType."'
			where ItemId='$ItemId'"; 
			
						  
				  $db->query($update);

		  
		     
	
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'items.php?msg=e_succ'
      //-->
      </script>"; 

}


$id=$_GET['cid'];
GetItemDetails($db,$id);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_items.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetItemDetails($db,$id)
 {
    global $ItemId,$ItemName,$ItemType,$STA,$HW;
    
    $sql="select * from epr_items where ItemId='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
		  $ItemName=stripslashes($rows['ItemName']);
		  $ItemType=GetCategory($db,$rows['ItemType']);
		  $ItemId=stripslashes($rows['ItemId']);
          
		  
 }

function GetCategory($db,$cat_id)
 {
	global $category;
	$sql="select * from epr_itemcategory order by CategoryId";
    $row=$db->query($sql);
      $category.="<select  name='ItemType'  style='font-family: kruti_dev_010regular;font-size:15px;' onchange='ShowItem(this.value)'>";
      $category.="<option value=''>pqu</option>";
	  while($res=$db->fetch_array($row))
       {
         $CategoryId = $res['CategoryId'];
         $CategoryName = $res['CategoryName'];
         if($CategoryId==$cat_id){
		    $category.="<option value='$CategoryId' style='font-family: kruti_dev_010regular;font-size:15px;' selected>$CategoryName</option>";
		 }else{
			$category.="<option value='$CategoryId' style='font-family: kruti_dev_010regular;font-size:15px;'>$CategoryName</option>";
		 }
       }
      $category.="</select>";
    
 }
?>
