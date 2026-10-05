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


	GetTenderDetails($db1,$TenderId,$db2);

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

	if($TenderStatus!='Pending'){
		$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/view_biddings.html");
	}else{
		$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/biddings.html");
	}
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
			  $sqlb="select ContractorId from cw_tender_bidded where TenderId=$TenderId order by BiddingRate ASC limit 1";
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

	function GetTenderDetails($db1,$Tid,$db2){
		
		global $db,$db2,$Status,$EstmtId,$MinRate,$TotalAmount,$WorkName,$WardNo,$ContractorName,$EstimatedAmount,$ContractorFirm,$AreaName,$TenderId,$amount,$strBiddingList,$amount,$TenderStatus;

		$sqlc="select * from cw_tenders where TenderId='".$Tid."'";
		$resc=$db1->query($sqlc);
		$t_rows=$db1->fetch_array($resc);
		
		$sqlb="select * from cw_tender_bidded where TenderId='".$Tid."' and Status='Final' order by BiddingAmount ASC";
		$resb=$db1->query($sqlb);
		$bid_num_rows=$db1->num_rows($resb);
		if($bid_num_rows!=0 && $t_rows['Status']!='Pending'){
			$strBiddingList='<table cellpadding="5" cellspacing="1" align="center"  class="tableList"><tr><th>S.No.</th><th>Tender Amount</th><th>Contractor Name</th><th>Rate</th><th>Final Amount</th><th>Deposite</th></tr>';
		}else{
			$strBiddingList='<table cellpadding="5" cellspacing="1" align="center"  class="tableList"><tr><th>S.No.</th><th>Tender Amount</th><th>Contractor Name</th><th>Rate</th><th>Final Amount</th><th>Deposite</th><th>Action</th></tr>';
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
		   if($t_rows['Status']!='Pending'){
			 $td='';
		   }else{
			  $td="<td><a href='assign_tender.php?cid=".$rows['TenderId']."&BidId=".$rowsb['TenderBidId']."'>Assign</a></td>";
		   }
		   
		   $strBiddingList.="<tr><td>$sno</td><td>".$rows['TotalAmount']."</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>".$rowsc['ContractorName']."</td><td>$Rate</td><td>$BiddingAmount</td><td>".$rowsb['DepositeType']."<br>".$rowsb['DepositeDetails']."</td>$td</tr>";
		   $i++;
		}
		$strBiddingList.="</table>";
		//print_r($rows);
		
		$sql="select t.*,t.Status as TStatus,e.*,b.* from cw_tender_bidded b INNER JOIN cw_tenders t ON b.TenderId=t.TenderId INNER JOIN cw_estimation e ON e.EstmtId=t.EstmtId where t.TenderId='".$Tid."' order by b.BiddingRate asc limit 1" ;
		$res=$db2->query($sql);
		$rows = $db2->fetch_array($res);

		$sqlc="select ContractorName from contractors where ContractorId='".$rows['ContractorId']."'";
		$resc=$db2->query($sqlc);
		$rowsc = $db2->fetch_array($resc);

		  $TenderId=$rows['TenderId'];
		  $TenderStatus=$rows['TStatus'];
		  $EstmtId=$rows['EstmtId'];
		  $WardNo=$rows['WardNo'];
		  $WorkName=$rows['WorkName'];
		  $EstimatedAmount=number_format($rows['TotalAmount'],3);
		  $ContractorName=$rowsc['ContractorName'];
		  $MinRate=$rows['BiddingRate'];
		  $amount=number_format($EstimatedAmount-($EstimatedAmount*$MinRate)/100,2);
		  $SubmitDate=$rows['SubmitDate'];
		  $SubmitDate = explode('-',$SubmitDate);
		  $SubmitDate=$SubmitDate[2]."/".$SubmitDate[1]."/".$SubmitDate[0]; 
		  //$amount=$rows['amount'];
		  $AreaName=$rows['AreaName'];
				  
	}
	?>
