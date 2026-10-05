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
include("../phplib/thumbclass.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

$TenderId=$_GET['cid'];
$TenderBidId=$_GET['BidId'];


GetTenderDetails($db1,$TenderId,$db2,$TenderBidId);

if($_POST['submit']!='')
 {
       $result=UpdateTender($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'tenderprocessed.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 //$regno_msg="Mad already exist.";
		  
	 }
 }
//echo $Status;
	$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/assign_tender.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function UpdateTender($db)
 {
		  extract($_POST);
		  //print_r($_POST);
		  $sqlb="select ContractorId from cw_tender_bidded where TenderId=$TenderId and TenderBidId=$TenderBidId";
		  $resb=$db->query($sqlb);
		  $rowsb = $db->fetch_array($resb);
		  $bContractorId=$rowsb['ContractorId'];

		  $sql="select * from cw_tender_bidded where TenderId=$TenderId";
		  $db->query($sql);
		  
		  $i=0;
		  $strContractorId='';
		  while($rows = $db->fetch_array()){
            if($rows['ContractorId']!=$bContractorId)   
				$strContractorId.=$rows['ContractorId'].',';
		    $i++;
		  }

		  $strContractorId=substr($strContractorId,0,-1);
		  
		  $WorkStartDate = explode('/',$WorkStartDate);
	      $WorkStartDate=$WorkStartDate[2]."-".$WorkStartDate[1]."-".$WorkStartDate[0]; 

		  $WorkEndDate = explode('/',$WorkEndDate);
	      $WorkEndDate=$WorkEndDate[2]."-".$WorkEndDate[1]."-".$WorkEndDate[0]; 

		  $update1="UPDATE cw_tenders SET Status='Won',ContractorId='$bContractorId',WorkOrderNo='$WorkOrderNo',WorkStartDate='$WorkStartDate',WorkEndDate='$WorkEndDate',FinalComments='".addslashes($FinalComments)."',appdate='".date('Y-m-d')."'  where TenderId='".$TenderId."'";
		  $db->query($update1);

		  $update2="UPDATE cw_tender_bidded SET TenderStatus='Loss' where ContractorId IN (".$strContractorId.")";
		  $db->query($update2);

		  $update3="UPDATE cw_tender_bidded SET TenderStatus='Won' where ContractorId IN (".$bContractorId.")";
		  $db->query($update3);
		  
		  $insert="insert into cw_progress (EstmtId,TenderId,WorkStartDate,WorkEndDate,WorkOrderNo) values('$EstmtId','$TenderId','$WorkStartDate','$WorkEndDate','$WorkOrderNo')";
		  $db->query($insert);
		  return true;
	 
 }

function GetTenderDetails($db1,$Tid,$db2,$TenderBidId){
	
	global $db,$db2,$Status,$EstmtId,$MinRate,$TotalAmount,$WorkName,$WardNo,$ContractorName,$EstimatedAmount,$ContractorFirm,$AreaName,$TenderId,$amount,$strBiddingList,$amount,$TenderStatus;

	
	$sqlb="select * from cw_tender_bidded where TenderId='".$Tid."' and TenderBidId='".$TenderBidId."'";
	$resb=$db1->query($sqlb);
	$bid_num_rows=$db1->num_rows($resb);
	if($bid_num_rows!=0){
	$strBiddingList='<table cellpadding="5" cellspacing="1" align="center"  class="tableList"><tr><th>S.No.</th><th>Tender Amount</th><th>Contractor Name</th><th>Rate</th><th>Final Amount</th><th>Deposite</th></tr>';
	}
	$i=0;
	
	while($rowsb = $db1->fetch_array($resb)){
       $sno=$i+1;
	   $sqlc="select * from contractors where ContractorId='".$rowsb['ContractorId']."'";
	   $resc=$db->query($sqlc);
	   $rowsc = $db->fetch_array($resc);

	   $sql="select t.*,e.* from cw_tenders t INNER JOIN cw_estimation e ON e.EstmtId=t.EstmtId where TenderId='".$Tid."'";
	   $res=$db2->query($sql);
	   $rows = $db2->fetch_array($res);

       $BidType=$rowsb['BidType'];
	   $Rate=$rowsb['BiddingRate']."&nbsp;($BidType)";
       $BiddingAmount=$rowsb['BiddingAmount'];
	   
	   $strBiddingList.="<tr><td>$sno</td><td>".$rows['TotalAmount']."</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>".$rowsc['ContractorName']."</td><td>$Rate</td><td>$BiddingAmount</td><td>".$rowsb['DepositeType']."<br>".$rowsb['DepositeDetails']."</td></tr>";
	   $i++;
	}
	$strBiddingList.="</table>";
	//print_r($rows);
	
	$sql="select t.*,e.*,b.* from cw_tender_bidded b INNER JOIN cw_tenders t ON b.TenderId=t.TenderId INNER JOIN cw_estimation e ON e.EstmtId=t.EstmtId where t.TenderId='".$Tid."' and b.TenderBidId='".$TenderBidId."'" ;
	$res=$db2->query($sql);
	$rows = $db2->fetch_array($res);

    $sqlc="select ContractorName from contractors where ContractorId='".$rows['ContractorId']."'";
	$resc=$db2->query($sqlc);
	$rowsc = $db2->fetch_array($resc);

	  $TenderId=$rows['TenderId'];
	  $Status=$rows['Status'];
	  $EstmtId=$rows['EstmtId'];
	  $WardNo=$rows['WardNo'];
	  $WorkName=$rows['WorkName'];
	  $TenderBidId=$rows['TenderBidId'];
	  $EstimatedAmount=$rows['TotalAmount'];
	  $ContractorName=$rowsc['ContractorName'];
	  $MinRate=$rows['BiddingRate']."%&nbsp;(".$rows['BidType'].")";
	  $amount=$rows['BiddingAmount'];
	  $SubmitDate=$rows['SubmitDate'];
	  $SubmitDate = explode('-',$SubmitDate);
	  $SubmitDate=$SubmitDate[2]."/".$SubmitDate[1]."/".$SubmitDate[0]; 
	  //$amount=$rows['amount'];
	  $AreaName=$rows['AreaName'];
              
}
?>
