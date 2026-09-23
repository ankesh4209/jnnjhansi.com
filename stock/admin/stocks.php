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
	$PROMPT='Stock Item has been added successfullly.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Stock Item has been edited successfullly.';
}
viewpage($db,$db1,$db2);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/stocks.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$db2)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$STotal,$STotal,$ItemId,$InvoiceNo,$PurchasingDate,$ChequeNo,$Qty,$Price,$ItemName,$strItemName,$strQty,$strPrice;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/stocksDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	  $count="SELECT count(t.total) as total FROM ( SELECT count(1) as total from `epr_stocks`group by FileNo) as t";
	 
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from epr_stocks where FileNo!=0 GROUP BY FileNo order by StockId DESC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $strItemName='';
			  $strQty='';
			  $strPrice='';
			  $STotal=0;
              $query1="select * from epr_stocks where FileNo='".$rows['FileNo']."'";
              $res1=$db1->query($query1);
			  while($rows1 = $db1->fetch_array($res1)){
				    $strItemName.=GetItemName($db2,$rows1['ItemId']).'<br>';
					$Qty=$rows1['Qty'];
					$strQty.=$rows1['Qty'].'<br>';
                    $Price=$rows1['Price'];
                    $strPrice.=$rows1['Price'].'<br>';
			        $STotal+=$Qty*$Price;
			  }
			  
			  //$StockId=stripslashes($rows['StockId']);
              $InvoiceNo=$rows['InvoiceNo'];
              $ItemId=$rows['ItemId'];
              $PurchasingDate=$rows['PurchasingDate'];
			  $PurchasingDate=explode('-',$PurchasingDate);
		      $PurchasingDate=$PurchasingDate[2]."/".$PurchasingDate[1]."/".$PurchasingDate[0];
		      $ChequeNo=$rows['ChequeNo'];
              
              $cid=$rows['InvoiceNo'];
			  
             

        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='stocks.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='stocks.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
					$PAGE_NAVS.=" <a href='stocks.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
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
    echo $sel_reg_id="delete from epr_stocks where InvoiceNo in ($product)"; die;
    $db->query($sel_reg_id);
	$total = $db->affected_rows();
  	$PROMPT = "Total $total Records have been deleted.";
 }

function GetItemName($db2,$ItemId)
 {
   //global $ItemName;
    $sql="select * from epr_items where ItemId='$ItemId' order by ItemName";
    $result=$db2->query($sql);
	$row=$db2->fetch_array();
    $ItemName=$row['ItemName'];
    return $ItemName;
 }

?>

