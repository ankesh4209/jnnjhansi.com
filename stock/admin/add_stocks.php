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
       $result=addStock($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'stocks.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="stock already exist.";
		  
	 }
 }

GetCategory($db);
GetItem($db);
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_stocks.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addStock($db)
 {
		  extract($_POST);

		  $PurchasingDate=explode('/',$PurchasingDate);
		  $PurchasingDate=$PurchasingDate[2]."-".$PurchasingDate[1]."-".$PurchasingDate[0];
		  $InvoiceNo=$InvoiceNo;
		  $ChequeNo=$ChequeNo;
		  $FileNo=$FileNo;
		  $FirmName=addslashes($FirmName);
		
		        $ItemType     = $_POST['ItemType'];
   		        $ItemId     = $_POST['ItemId'];
				$Qty    = $_POST['Qty'];
				$Price      = $_POST['Price'];
				for($i=0;$i<count($ItemType);$i++)
				{
					 
					 if($ItemType[$i]!="" && $ItemId[$i]!=""  && $Qty[$i]!=""  && $Price[$i]!="")
					  { 
						$insert="insert into epr_stocks(ItemType,ItemId,PurchasingDate,FirmName,InvoiceNo,ChequeNo,Qty,Price,FileNo) values('".$ItemType[$i]."','".$ItemId[$i]."','$PurchasingDate','$FirmName','$InvoiceNo','$ChequeNo','".$Qty[$i]."','".$Price[$i]."','$FileNo')";
						
						$db->query($insert);
		 		
					  }
				}
				
		  
		 return true;
	 
 }


function GetCategory($db)
 {
	global $category;
	$sql="select * from epr_itemcategory order by CategoryName";
    $row=$db->query($sql);
      $category.="<select  name='ItemType[]'    onchange=ShowItems(this)>";
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
