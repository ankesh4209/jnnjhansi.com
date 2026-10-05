<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: ../login.php"); 				
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


if(isset($_GET['msg']) && $_GET['msg']=='edit')
{
	$PROMPT='Tender has been edited successfullly.';
}

viewpage($db,$db1);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/tenders.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$TotalCnt,$TotalAmount,$WorkName,$WardNo,$TenderStartDate,$TenderEndDate,$BiddingStartDate,$BiddingEndDate,$TenderCategory,$PurchaseAmount,$action,$Comments;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/tendersDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	$count="select count(TenderId) as total from cw_tenders";
	 
    $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
    $query="select * from cw_tenders order by TenderId DESC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
              $sql="SELECT * FROM cw_estimation where EstmtId='".$rows['EstmtId']."'";
              $res1=$db1->query($sql);
			  $row1 = $db1->fetch_array();
//echo '<pre>';
//print_r($row1);

			  
			  $cid=$rows['TenderId'];
			  $WardNo=$row1['WardNo'];
              $WorkName=$row1['WorkName'];
              $TotalAmount=$row1['TotalAmount'];
             
			  $TenderCategory=$rows['TenderCategory'];
			  $PurchaseAmount=$rows['PurchaseAmount'];
			  $Comments=$rows['Comments'];
			  
			  $TenderStartDate=date('d/m/Y h A',$rows['TenderStart']);
			  
			  $TenderEndDate=date('d/m/Y h A',$rows['TenderEnd']);
			 
			  $BiddingStartDate=date('d/m/Y h A',$rows['BiddingStart']);
			  
			  
			  $BiddingEndDate=date('d/m/Y h A',$rows['BiddingEnd']);
			  
              $purchase_sql="select count(*) as cnt from cw_tender_purchased where TenderId=".$cid;
              $purchase_res=$db1->query($purchase_sql);
			  $purchase_rows=$db1->fetch_array($purchase_res);
			  $cnt=$purchase_rows['cnt'];
			  //if($cnt>0){
			    // $action="";
			     //$action="<a href='view_tender.php?cid=$cid'>View</a>";
			  //}else{
				  $action="<a href='edit_tender.php?cid=$cid'>Edit</a>";
			  //}
			  
        
	        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='tenders.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='tenders.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='tenders.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='11'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($cid, $db)
 {	
	global $PROMPT;

   	$product = implode(",", $cid);
    $sel_reg_id="delete from cw_tenders where AmtId in ($product)"; 
    $db->query($sel_reg_id);
	$total = $db->affected_rows();
  	$PROMPT = "Total $total Records have been deleted.";
 }


?>

