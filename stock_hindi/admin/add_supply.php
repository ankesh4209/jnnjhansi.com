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
       $result=addSupply($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'supply.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="supply items already exist.";
		  
	 }
 }

GetCategory($db);
GetItem($db,$db1);
GetDepartments($db);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_supply.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addSupply($db)
 {
		  extract($_POST);

		  $SupplyDate=explode('/',$SupplyDate);
		  $SupplyDate=$SupplyDate[2]."-".$SupplyDate[1]."-".$SupplyDate[0];
		  $cur_time=mktime(date('G'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		        $ItemId     = $_POST['ItemId'];
				$Qty    = $_POST['Qty'];
				$ItemType    = $_POST['ItemType'];

				for($i=0;$i<count($ItemId);$i++)
				{
					 
					 if($ItemId[$i]!=""  && $Qty[$i]!="" && $ItemType[$i]!="")
					  { 
						$insert="insert into epr_distributions(ItemType,ItemId,DistributionDate,ReceiverName,DeliverQty,DepartmentId,AddedDateTime) values('".$ItemType[$i]."','".$ItemId[$i]."','".$SupplyDate."','".$ReceiverName."','".$Qty[$i]."','".$DepartmentId."','".$cur_time."')";
						
						$db->query($insert);
		 		
					  }
				}
				
		  
		 return true;
	 
 }


function GetCategory($db)
 {
	global $category;
	$sql="select * from epr_itemcategory order by CategoryId";
    $row=$db->query($sql);
      $category.="<select  name='ItemType[]'  style='font-family: kruti_dev_010regular;font-size:15px;' onchange='ShowItems(this)'>";
      $category.="<option value=''>lkexzh dk izdkj pqu</option>";
	  while($res=$db->fetch_array($row))
       {
         $CategoryId = $res['CategoryId'];
         $CategoryName = $res['CategoryName'];
        
			$category.="<option value='$CategoryId' style='font-family: kruti_dev_010regular;font-size:15px;'>$CategoryName</option>";
		 
       }
      $category.="</select>";
    
 }

 function GetItem($db,$db1)
 {
	global $edit_items;
	$sql="SELECT i.ItemId as ItemId,i.ItemName as ItemName,s.Qty as Qty FROM epr_items as i INNER JOIN epr_stocks as s ON i.ItemId=s.ItemId ";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      $edit_items.="<select  name='ItemId[]'  id='ItemId[0]' style='font-family: kruti_dev_010regular;font-size:15px;' >";
      $edit_items.="<option value=''>lkexzh pqu</option>";
	  while($res=$db->fetch_array($row))
       {
         $item_id = $res['ItemId'];
         $ItemName = $res['ItemName'];
         $Qty = $res['Qty'];

		 $sql1="SELECT sum(DeliverQty) as DeliverQty FROM epr_distributions Where ItemId='".$item_id."'";
		 $row1=$db1->query($sql1);
		 $res1=$db1->fetch_array($row1);
		 $RemainQty=$Qty-$res1['DeliverQty'];
         	$edit_items.="<option value='$item_id' >$ItemName $RemainQty</option>";
		 
       }
      $edit_items.="</select>";
    }else{
		$edit_items.="<select  name='ItemId[]'  style='font-family: kruti_dev_010regular;font-size:15px;' >";
		$edit_items.="<option value=''>lkexzh pqu</option>";
		$edit_items.="</select>";
	  
	}
 }


 function GetDepartments($db)
 {
	global $departments;
	$sql="select * from department order by DeptName ASC";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      $departments.="<select  name='DepartmentId'  style='font-family: kruti_dev_010regular;font-size:15px;' >";
      $departments.="<option value=''>foHkkx pqu</option>";
	  while($res=$db->fetch_array($row))
       {
         $DepartmentId = $res['DeptId'];
         $DepartmentName = $res['DeptName'];
         	$departments.="<option value='$DepartmentId' style='font-family: kruti_dev_010regular;font-size:15px;'>$DepartmentName</option>";
		 
       }
      $departments.="</select>";
    }
 }

?>
