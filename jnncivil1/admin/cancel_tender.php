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
		 $regno_msg="Mad already exist.";
		  
	 }
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/cancel_tender.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function UpdateTender($db)
 {
		  extract($_POST);
		 // print_r($_POST);
		 
		  $update1="UPDATE cw_tenders SET Status='Cancelled',FinalComments='".addslashes($FinalComments)."',appdate='".date('Y-m-d')."'  where TenderId='".$TenderId."'";
		  $db->query($update1);

		  $update2="UPDATE cw_tender_bidded SET TenderStatus='Cancelled' where TenderId='".$TenderId."'";
		  $db->query($update2);

		  
 }

function GetTenderDetails($db1,$Tid,$db2){
	
	global $db,$db2,$Status,$EstmtId,$MinRate,$TotalAmount,$WorkName,$WardNo,$ContractorName,$EstimatedAmount,$ContractorFirm,$AreaName,$TenderId,$amount,$strBiddingList,$amount;

	
	$sqlb="select * from cw_tender_bidded where TenderId='".$Tid."' and Status='Final'";
	$resb=$db1->query($sqlb);
	$strBiddingList='<table cellpadding="5" cellspacing="1" align="center"  class="tableList"><tr><th>S.No.</th><th>Tender Amount</th><th>Contractor Name</th><th>Rate</th><th>Final Amount</th><th>Deposite</th></tr>';
	$i=0;
	
	while($rowsb = $db1->fetch_array($resb)){
       $sno=$i+1;
	   $sqlc="select * from contractors where ContractorId='".$rowsb['ContractorId']."'";
	   $resc=$db->query($sqlc);
	   $rowsc = $db->fetch_array($resc);

	   $sql="select t.*,e.* from cw_tenders t INNER JOIN cw_estimation e ON e.EstmtId=t.EstmtId where TenderId='".$Tid."'";
	   $res=$db2->query($sql);
	   $rows = $db2->fetch_array($res);

	   $Rate=$rowsb['BiddingRate'];
       $FianlAmt=$rows['TotalAmount']-($rows['TotalAmount']*$Rate)/100;
	   $strBiddingList.="<tr><td>$sno</td><td>".$rows['TotalAmount']."</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>".$rowsc['ContractorName']."</td><td>$Rate</td><td>$FianlAmt</td><td>".$rowsb['DepositeType']."<br>".$rowsb['DepositeDetails']."</td></tr>";
	   $i++;
	}
	$strBiddingList.="</table>";
	//print_r($rows);
	
	$sql="select t.*,e.*,b.* from cw_tender_bidded b INNER JOIN cw_tenders t ON b.TenderId=t.TenderId INNER JOIN cw_estimation e ON e.EstmtId=t.EstmtId where t.TenderId='".$Tid."' order by b.BiddingRate asc limit 1" ;
	$res=$db2->query($sql);
	$rows = $db2->fetch_array($res);

    $sqlc="select ContractorName from contractors where ContractorId='".$rows['ContractorId']."'";
	$resc=$db2->query($sqlc);
	$rowsc = $db2->fetch_array($resc);

	$TenderId=$rows['TenderId'];
	  $EstmtId=$rows['EstmtId'];
	  $WardNo=$rows['WardNo'];
	  $WorkName=$rows['WorkName'];
	  $EstimatedAmount=$rows['TotalAmount'];
	  $ContractorName=$rowsc['ContractorName'];
	  $MinRate=$rows['BiddingRate'];
	  $amount=$EstimatedAmount-($EstimatedAmount*$MinRate)/100;
	  $SubmitDate=$rows['SubmitDate'];
	  $SubmitDate = explode('-',$SubmitDate);
	  $SubmitDate=$SubmitDate[2]."/".$SubmitDate[1]."/".$SubmitDate[0]; 
	  //$amount=$rows['amount'];
	  $AreaName=$rows['AreaName'];
              
}
?>
