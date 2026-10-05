<?php 
session_start();	
 if ($_SESSION['ContractorId']=='')
  {	
    header ("Location: contractor_login.php"); 				
	  exit;
  }
include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

if($_GET['msg']=='succ'){
   $PROMPT='Bidding has been saved successfullly.';
}
if($_GET['msg']=='succ_sub'){
   $PROMPT='Bidding has been submited successfullly.';
}
viewTenders($db,$db1);

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/bidded_tenders.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/contra_template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/contra_bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/contra_topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewTenders($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid,$trclass;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$TotalCnt,$TotalAmount,$WorkName,$WardNo,$TenderStartDate,$TenderEndDate,$BiddingStartDate,$BiddingEndDate,$TenderCategory,$BiddingRate,$BiddingAmount,$TenderBidId,$Final,$TenderStatus;
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/biddedtendersDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 $cur_time=mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'))+19800;
	 $count="select count(TenderId) as total from cw_tender_bidded where   ContractorId=".$_SESSION['ContractorId']." order by BiddingDate DESC";
	 
    $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
    $query="select * from cw_tender_bidded where   ContractorId=".$_SESSION['ContractorId']." order by BiddingDate DESC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
              $sql="SELECT * FROM cw_estimation e INNER JOIN cw_tenders t ON t.EstmtId=e.EstmtId  where t.TenderId='".$rows['TenderId']."'";
              $res1=$db1->query($sql);
			  $row1 = $db1->fetch_array();
//echo '<pre>';
//print_r($row1);

			  
			  $cid=$rows['TenderId'];
			  $WardNo=$row1['WardNo'];
              $WorkName=$row1['WorkName'];
              $TotalAmount=$row1['TotalAmount'];
              $BidType=$rows['BidType'];
              $BiddingAmount=$rows['BiddingAmount'];
             
			  $TenderCategory=$rows['TenderCategory'];
			  $PurchaseAmount=$rows['PurchaseAmount'];
			  $BiddingRate=$rows['BiddingRate']."&nbsp;($BidType)";
			  $TenderBidId=$rows['TenderBidId'];
			  $BiddingEnd=$row1['BiddingEnd'];
			  $Final=$rows['Status'];
			  $TenderStatus=$rows['TenderStatus'];
			  if($Final=='Final'){
			    $Final='Bidded';
			  }else{
				  
                 if($BiddingEnd>=$cur_time){      
					if($Final=='Final'){
						$Final='Bidded';
			        }
					$Final="<a href='edit_bidding.php?cid=$cid'>Edit</a>/<a href='final_bidding.php?TenderBidId=$TenderBidId&cid=$cid'>Submit</a>";
				 }else{
					 $Final="Bidding Ended";
				 }
			  }
			  
			  
			  $BiddingStartDate=date('d/m/Y h A',$row1['BiddingStart']);
			 
			  
			  $BiddingEndDate=date('d/m/Y h A',$row1['BiddingEnd']);
			 
			  if($slno%2==1){
				  $trclass='style="background:#e6e6e6;" onmouseout="style.backgroundColor=\'#e6e6e6\'" onmouseover="style.backgroundColor=\'#84DFC1\';"';
			  }
			  else{
				 $trclass='style="background:#f7f7f7;" onmouseout="style.backgroundColor=\'#f7f7f7\'" onmouseover="style.backgroundColor=\'#84DFC1\';"';
			  }
			  
			  /*$purchase_sql="select count(*) as cnt from cw_tender_purchased where ContractorId=".$_SESSION['ContractorId']." and TenderId=".$cid;
              $purchase_res=$db->query($purchase_sql);
			  $purchase_rows=$db->fetch_array($purchase_res);
			  $cnt=$purchase_rows['cnt'];
			  if($cnt>0){
			     $action='Purchased';
			  }else{
				  $action='Purchased';
			  }*/

        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='bidded_tenders.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='bidded_tenders.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='bidded_tenders.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
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
?>
