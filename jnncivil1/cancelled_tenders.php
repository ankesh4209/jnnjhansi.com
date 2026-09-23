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

viewTenders($db,$db1);

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/cancelled_tenders.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/contra_template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/contra_bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/contra_topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewTenders($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid,$trclass;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$TotalCnt,$TotalAmount,$WorkName,$WardNo,$TenderStartDate,$TenderEndDate,$BiddingStartDate,$BiddingEndDate,$TenderCategory,$BiddingRate,$BiddingAmount,$TenderBidId,$DepositeType,$DepositeDetails,$WorkEndDate,$WorkStartDate,$WorkOrderNo,$FinalComments;
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/cancelledtendersDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 $cur_time=mktime(date('G'),0,0,date('m'),date('d'),date('Y'));
	 $count="select count(TenderId) as total from cw_tender_bidded where TenderStatus='Cancelled' and ContractorId=".$_SESSION['ContractorId']." order by BiddingDate DESC";
	 
    $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
    $query="select * from cw_tender_bidded where  TenderStatus='Cancelled' and ContractorId=".$_SESSION['ContractorId']." order by BiddingDate DESC limit  $page, $MAX ";
    
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
              $sql1="SELECT * FROM cw_estimation e INNER JOIN cw_tenders t ON t.EstmtId=e.EstmtId  where t.TenderId='".$rows['TenderId']."'";
              $res2=$db1->query($sql1);
			  $row2 = $db1->fetch_array();

			  $WorkOrderNo=$row2['WorkOrderNo'];
			  $WorkStartDate=$row2['WorkStartDate'];
			  $WorkEndDate=$row2['WorkEndDate'];
			  $FinalComments=$row2['FinalComments'];

              $WorkStartDate=date('d/m/Y',strtotime($row2['WorkStartDate']));
              $WorkEndDate=date('d/m/Y',strtotime($row2['WorkEndDate']));
			  
			  $cid=$rows['TenderId'];
			  $WardNo=$row1['WardNo'];
              $WorkName=$row1['WorkName'];
              $TotalAmount=$row1['TotalAmount'];
             
			  $TenderCategory=$rows['TenderCategory'];
			  $PurchaseAmount=$rows['PurchaseAmount'];
			  $BiddingRate=$rows['BiddingRate'];
			  $TenderBidId=$rows['TenderBidId'];
			  $BiddingEnd=$rows['BiddingEnd'];
			  $Final=$rows['Status'];
			  if($Final=='Final'){
			    $Final='Bidded';
			  }else{
                 if($BiddingEnd>=$cur_time){      
			     $Final="<a href='edit_bidding.php?cid=$cid'>Edit</a>/<a href='final_bidding.php?TenderBidId=$TenderBidId&cid=$cid'>Submit</a>";
				 }else{
					 $Final="Bidding Ended";
				 }
			  }
			  $BiddingAmount=$TotalAmount-($TotalAmount*$BiddingRate)/100;
			  
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
				$PREV_PAGE_LINK="<<a href='purchased_tenders.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='purchased_tenders.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='purchased_tenders.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
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
