<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: index.php"); 				
	  exit;
  }
?>

<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/thumbclass.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());



if($_POST['submit']!='')
 {
       $result=addItem($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'items.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="Item already exist.";
		  
	 }
 }

GetCategory($db);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_items.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addItem($db)
 {
		  extract($_POST);
		  $ItemName=addslashes($ItemName);
		  $ItemType=addslashes($ItemType);
		  
		  
		     
		  $insert="insert into epr_items(ItemType,ItemName) values('$ItemType','$ItemName')";
		  $db->query($insert);
		  
		 return true;
	 
 }

function GetCategory($db)
 {
	global $category;
	$sql="select * from epr_itemcategory order by CategoryName";
    $row=$db->query($sql);
      $category.="<select  name='ItemType'   onchange='ShowItem(this.value)'>";
      $category.="<option value=''>Choose</option>";
	  while($res=$db->fetch_array($row))
       {
         $CategoryId = $res['CategoryId'];
         $CategoryName = $res['CategoryName'];
        
			$category.="<option value='$CategoryId' >$CategoryName</option>";
		 
       }
      $category.="</select>";
    
 }
?>
