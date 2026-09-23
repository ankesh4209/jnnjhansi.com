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
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());



if($_POST['submit']!='')
 {
       $result=addItem($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'bitems.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="Item already exist.";
		  
	 }
 }

GetCategory($db);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_bitems.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addItem($db)
 {
		  extract($_POST);
		  
		  
		     
		  $insert="insert into epr_backlog_items(ItemType,ItemId,B_Qty) values('$ItemType','$ItemId','$B_Qty')";
		  $db->query($insert);

		  $insert1="insert into epr_stocks(ItemType,ItemId,Qty) values('$ItemType','$ItemId','$B_Qty')";
		  $db->query($insert1);
		  
		 return true;
	 
 }

function GetCategory($db)
 {
	global $category;
	$sql="select * from epr_itemcategory order by CategoryName";
    $row=$db->query($sql);
      $category.="<select  name='ItemType'    onchange=ShowItems(this)>";
      $category.="<option value=''>Choose</option>";
	  while($res=$db->fetch_array($row))
       {
         $CategoryId = $res['CategoryId'];
         $CategoryName = $res['CategoryName'];
        
			$category.="<option value='$CategoryId' >$CategoryName</option>";
		 
       }
      $category.="</select>";
    
 }

function GetItem($db)
 {
	global $edit_items;
	$sql="select * from epr_items order by ItemName";
    $row=$db->query($sql);
    if($db->num_rows())
    {
      $edit_items.="<select  name='ItemId[]'  id='ItemId[0]'  >";
      $edit_items.="<option value='' >Choose</option>";
	  while($res=$db->fetch_array($row))
       {
         $item_id = $res['ItemId'];
         $ItemName = $res['ItemName'];
         	$edit_items.="<option value='$item_id' >$ItemName</option>";
		 
       }
      $edit_items.="</select>";
    }else{
		$edit_items.="<select  name='ItemId[]'   >";
		$edit_items.="<option value=''>Choose</option>";
		$edit_items.="</select>";
	  
	}
 }
?>
