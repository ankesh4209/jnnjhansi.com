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

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/tenders.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/contra_template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/contra_bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/contra_topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewTenders($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid,$trclass;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$TotalCnt,$TotalAmount,$WorkName,$WardNo,$TenderStartDate,$TenderEndDate,$BiddingStartDate,$BiddingEndDate,$TenderCategory,$PurchaseAmount,$action,$Vat,$Comments;
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/tendersDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 $cur_time=mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'))+19800;
	  $count="select count(TenderId) as total from cw_tenders where  TenderStart<=".$cur_time." and TenderEnd>=".$cur_time." and TenderCategory='".$_SESSION['hashiyatkishreni']."'";
	 
    $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
    $query="select * from cw_tenders where TenderStart<=".$cur_time." and TenderEnd>=".$cur_time." and   TenderCategory='".$_SESSION['hashiyatkishreni']."' order by TenderId DESC limit  $page, $MAX ";
    
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
			  $Vat=$rows['Vat'];
			  $Comments=nl2br($rows['Comments']);
			  
			  $TenderStartDate=date('d/m/Y h A',$rows['TenderStart']);
			  
			  $TenderEndDate=date('d/m/Y h A',$rows['TenderEnd']);
			 
			  $BiddingStartDate=date('d/m/Y h A',$rows['BiddingStart']);
			 
			  
			  $BiddingEndDate=date('d/m/Y h A',$rows['BiddingEnd']);
			 
			  if($slno%2==1){
				  $trclass='style="background:#e6e6e6;" onmouseout="style.backgroundColor=\'#e6e6e6\'" onmouseover="style.backgroundColor=\'#84DFC1\';"';
			  }
			  else{
				 $trclass='style="background:#f7f7f7;" onmouseout="style.backgroundColor=\'#f7f7f7\'" onmouseover="style.backgroundColor=\'#84DFC1\';"';
			  }
			  
              $purchase_sql="select count(*) as cnt from cw_tender_purchased where ContractorId=".$_SESSION['ContractorId']." and TenderId=".$cid;
              $purchase_res=$db->query($purchase_sql);
			  $purchase_rows=$db->fetch_array($purchase_res);
			  $cnt=$purchase_rows['cnt'];
			  if($cnt>0){
			     $action='<span style=color:blue;bold">Purchased</span>';
			  }else{
				  $action="<a href='tender_details.php?cid=$cid'>Purcahse</a>";
			  }

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
      $PRODUCT_LIST="<tr><td colspan='10'>No Records Found</td></tr>";
    }
   
  return 1;
 }
?>
