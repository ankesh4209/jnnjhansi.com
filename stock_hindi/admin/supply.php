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

$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

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
	$PROMPT='Supply Item has been added successfullly.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Supply Item has been edited successfullly.';
}
viewpage($db,$db1,$db2);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/supply.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$db2)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$ItemId,$DistributionDate,$DistributionId,$Qty,$ItemName,$ReceiverName,$DepartmentName,$strItemName,$strQty;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/supplyDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	  $count="select count(t.total) as total from ( SELECT count(1) as total from `epr_distributions` group by DepartmentId,AddedDateTime ) as t";
	 
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from epr_distributions group by DepartmentId,AddedDateTime order by DistributionId ASC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
              $strItemName='';
			  $strQty='';
			  $query1="select * from epr_distributions where AddedDateTime='".$rows['AddedDateTime']."'";
              $res1=$db1->query($query1);
			  while($rows1 = $db1->fetch_array($res1)){
				    $strItemName.=GetItemName($db2,$rows1['ItemId']).'<br>';
					$DeliverQty=$rows1['DeliverQty'];
					$strQty.=$rows1['DeliverQty'].'<br>';
                    
			  }
			  $DistributionId=$rows['DistributionId'];
              $ItemId=$rows['ItemId'];
              $DistributionDate=$rows['DistributionDate'];
              $AddedDate=$rows['AddedDate'];
			  $DistributionDate=explode('-',$DistributionDate);
		      $DistributionDate=$DistributionDate[2]."/".$DistributionDate[1]."/".$DistributionDate[0];
		      $Qty=$rows['DeliverQty'];
		      $ReceiverName=$rows['ReceiverName'];
		      $DepartmentId=$rows['DepartmentId'];
              $cid=$rows['AddedDateTime'];
			  
             $ItemName=GetItemName($db1,$ItemId);
             $DepartmentName=GetDeptName($db1,$DepartmentId);

        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='supply.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='supply.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='supply.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='10'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($cid, $db)
 {	
	global $PROMPT;

   	$product = implode(",", $cid);
    $sel_reg_id="delete from epr_distributions where AddedDateTime in ($product)"; 
    $db->query($sel_reg_id);
	$total = $db->affected_rows();
  	$PROMPT = "Total $total Records have been deleted.";
 }

function GetItemName($db1,$ItemId)
 {
   //global $ItemName;
    $sql="select * from epr_items where ItemId='$ItemId'";
    $result=$db1->query($sql);
	$row=$db1->fetch_array();
    $ItemName=$row['ItemName'];
    return $ItemName;
 }


 function GetDeptName($db1,$DepartmentId)
 {
   //global $ItemName;
    $sql="select * from department where DeptId='".$DepartmentId."'";
    $result=$db1->query($sql);
	$row=$db1->fetch_array();
    $DepartmentName=$row['DeptName'];
    return $DepartmentName;
 }

?>

