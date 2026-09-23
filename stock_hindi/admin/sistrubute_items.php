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
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$cid = $_REQUEST['cid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($cid))
	{
	 
		deleteaboutus($cid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($cid))
	{
		ChangeStatus($cid, $db);
	}
}


if(isset($_GET['msg']) && $_GET['msg']=='succ')
{
	$PROMPT='Item has been added successfullly.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Item has been edited successfullly.';
}
$ItemTypeId=$_GET['ItemTypeId'];
$category=GetCategory($db,$ItemTypeId);
viewpage($db,$db1,$rid);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/item_list.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$rid)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$ItemId,$ItemType,$ItemName,$Qty,$DeliverQty,$RemainQty;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/itemlistDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	   $page = 0 ;
	   $MAX=10;
	 $lastrow=$MAX+$page;

	 if($_GET['ItemTypeId']!=''){
	   $sql="and i.ItemType='".$_GET['ItemTypeId']."'";
	   $ItemTypeId=$_GET['ItemTypeId'];
	 }
	 
	   $count="SELECT count(*) as total FROM epr_items as i INNER JOIN epr_stocks as s ON i.ItemId=s.ItemId where 1 $sql";
	 
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="SELECT i.ItemId as ItemId,i.ItemName as ItemName,s.Qty as Qty,i.ItemType as ItemType FROM epr_items as i INNER JOIN epr_stocks as s ON i.ItemId=s.ItemId where 1 $sql order by ItemId DESC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
              $ItemName=stripslashes($rows['ItemName']);
              $Qty=$rows['Qty'];
              $ItemId=stripslashes($rows['ItemId']);
              $ItemType=GetCategoryName($db1,$rows['ItemType']);
              $cid=$rows['ItemId'];

			  $sql1="SELECT sum(DeliverQty) as DeliverQty FROM epr_distributions Where ItemId='".$ItemId."'";
		      $row1=$db1->query($sql1);
		      $res1=$db1->fetch_array($row1);
		      $DeliverQty=$res1['DeliverQty'];
		      if($DeliverQty==NULL){
			    $DeliverQty=0;
			  }
			  $RemainQty=$Qty-$res1['DeliverQty'];

			  
        
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='item_list.php?page=$prevpage&max=$MAX&ItemTypeId=$ItemTypeId' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='item_list.php?page=$lastrow&max=$MAX&ItemTypeId=$ItemTypeId' >Next></a>";
			}
							
			$PAGE_NAVS="";
			for($i=0,$toPrint=1;$i<	$TOTAL_RECORDSET;$i+=$MAX,$toPrint++)
			{	
       if ($lastrow-$i==$MAX)
				{	
          $PAGE_NAVS.=" <B>".$toPrint."</b> | ";
					$CURRENT_PAGE_NO = $toPrint;
				}
				else
				{	
          $PAGE_NAVS.=" <a href='item_list.php?page=$i&max=$MAX&ItemTypeId=$ItemTypeId' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='6'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($cid, $db)
 {	
	global $PROMPT;

   	$product = implode(",", $cid);
    $sel_reg_id="delete from epr_items where ItemId in ($product)"; 
    $db->query($sel_reg_id);
	$total = $db->affected_rows();
  	$PROMPT = "Total $total Records have been deleted.";
 }

function GetCategoryName($db1,$Cat_id)
 {

	$sql="select * from epr_itemcategory where CategoryId='$Cat_id'";
    $row=$db1->query($sql);
      $res=$db1->fetch_array($row);
      $CategoryName = $res['CategoryName'];
      return $CategoryName;  
    
 }

 function GetCategory($db,$ItemTypeId)
 {
	global $category;
	
	//where CategoryId='$ItemType'
	$sql="select * from epr_itemcategory order by CategoryId";
    $row=$db->query($sql);
      $category.="<select  name='ItemTypeId'  style='font-family: kruti_dev_010regular;font-size:15px;' onchange='document.page1.submit();'>";
      $category.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
         $CategoryId = $res['CategoryId'];
         $CategoryName = $res['CategoryName'];
        if($CategoryId==$ItemTypeId){
			$category.="<option value='$CategoryId' style='font-family: kruti_dev_010regular;font-size:15px;' selected>$CategoryName</option>";
		}else{
			$category.="<option value='$CategoryId' style='font-family: kruti_dev_010regular;font-size:15px;'>$CategoryName</option>";
		}
       }
      $category.="</select>";
	  return $category;
    
 }
?>

