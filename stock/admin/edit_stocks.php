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

$id=$_GET['cid'];
GetStockDetails($db,$db1,$id);

if($_POST['submit']!='')
 {
       $result=editStock($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'stocks.php?msg=e_succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="stock already exist.";
		  
	 }
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_stocks.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function editStock($db)
 {
		  extract($_POST);

		 $PurchasingDate=explode('/',$PurchasingDate);
		  $PurchasingDate=$PurchasingDate[2]."-".$PurchasingDate[1]."-".$PurchasingDate[0];
		  $InvoiceNo=$InvoiceNo;
		  $ChequeNo=$ChequeNo;
		
		        $ItemType     = $_POST['ItemType'];
   		        $ItemId     = $_POST['ItemId'];
				$Qty    = $_POST['Qty'];
				$Price      = $_POST['Price'];
				
				$sql_del='delete from epr_stocks where InvoiceNo="'.$cid.'"';
				$db->query($sql_del);

				for($i=0;$i<count($ItemType);$i++)
				{
					 
					 if($ItemType[$i]!="" && $ItemId[$i]!=""  && $Qty[$i]!=""  && $Price[$i]!="")
					  { 
						$insert="insert into epr_stocks(ItemType,ItemId,PurchasingDate,FirmName,InvoiceNo,ChequeNo,Qty,Price) values('".$ItemType[$i]."','".$ItemId[$i]."','$PurchasingDate','$FirmName','$InvoiceNo','$ChequeNo','".$Qty[$i]."','".$Price[$i]."')";
						
						$db->query($insert);
		 		
					  }
				}
			 
		 return true;
	 
 }
 
 function GetStockDetails($db,$db1,$StockId){
 
	global $ItemType,$STA,$HW,$InvoiceNo,$PurchasingDate,$ChequeNo,$Qty,$Price,$sid,$strRows,$FirmName; 
	$sql="select * from epr_stocks where InvoiceNo='".$StockId."' order by StockId";
    $res=$db->query($sql);
	$rows=$db->fetch_array($res);
   
    $sid=$rows['StockId'];
    $ItemId=$rows['ItemId'];
    $ItemType=$rows['ItemType'];
    $FirmName=$rows['FirmName'];
    $InvoiceNo=$rows['InvoiceNo'];
    $PurchasingDate=$rows['PurchasingDate'];
	$PurchasingDate=explode('-',$PurchasingDate);
	$PurchasingDate=$PurchasingDate[2]."/".$PurchasingDate[1]."/".$PurchasingDate[0];
    $ChequeNo=$rows['ChequeNo'];
    //GetCategory($db,$ItemType);
	//GetItem($db,$ItemType,$ItemId);
    $i=0;
	$res1=$db1->query($sql);
	
	while($rows1=$db1->fetch_array($res1)){ //echo $i;
       $cat=GetCategory($db,$rows1['ItemType']);
       $items=GetItem($db,$rows1['ItemId']);
	   $Qty=$rows1['Qty'];
	   $Price=$rows1['Price'];
	
    $strRows.="<tr><td width='30%' align='left'>$cat</td>
								<td width='30%' align='left'>$items</td>
								<td width='20%' align='left'> <input name='Qty[]' type='text' class='inp' value='$Qty' size='7'></td><td width='20%' align='left'><input name='Price[]' type='text' class='inp'  value='$Price' size='7'></td></tr>";
	$i++;
	}		
 }


 function GetCategory($db,$ItemType)
 {
	//global $category;
	$sql="select * from epr_itemcategory order by CategoryName";
    $row=$db->query($sql);
      $category.="<select  name='ItemType[]'    onchange=ShowItems(this)>";
      $category.="<option value=''>Choose</option>";
	  while($res=$db->fetch_array($row))
       {
         $CategoryId = $res['CategoryId'];
         $CategoryName = $res['CategoryName'];
            if($ItemType==$CategoryId){
			  $category.="<option value='$CategoryId'  selected>$CategoryName</option>";
			}else{
			  $category.="<option value='$CategoryId' >$CategoryName</option>";
			}
		 
       }
      $category.="</select>";
	  return $category;
    
 }

  function GetItem($db,$ItemId)
 {
	//global $edit_items;
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
         	if($ItemId==$item_id){
			    $edit_items.="<option value='$item_id'  selected>$ItemName</option>";
			}else{
				$edit_items.="<option value='$item_id' >$ItemName</option>";
			}
		 
       }
      $edit_items.="</select>";
    }else{
		$edit_items.="<select  name='ItemId[]'   >";
		$edit_items.="<option value=''>Choose</option>";
		$edit_items.="</select>";
	  
	}

	return $edit_items;
 }

?>
