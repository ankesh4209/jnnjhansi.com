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

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$cid = $_REQUEST['cid'];
if($_POST['BackUrl']==''){
	$BackUrl= $_SERVER['HTTP_REFERER'];
}else{
	$BackUrl= $_POST['BackUrl'];
}
//print_r($_POST);
if($_POST["submit"]=='Submit Bid')
{
 		$result=SubmitBid( $db,$cid);
		if($result){
			echo "<script type='text/javascript'>
        <!-- 
         window.location = 'bidded_tenders.php?msg=succ_sub'
        //-->
        </script>";
			//$PROMPT='Bidding has been submited successfullly.';
		}else{
			$PROMPT='Bidding could not be submited.';
		}
	viewpage($db,$db1,$cid);

}




viewpage($db,$db1,$cid);

 
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/final_bidding.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/contra_template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/contra_bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/contra_topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$cid)
 {
   global $cid,$status,$MadId,$MadName,$TotalAmount,$WorkName,$WardNo,$AreaName,$AddedDate,$Status,$Approved,$Cancelled,$Pending,$JEInfo,$AEInfo,$EXnInfo,$ProposerName,$TenderStartDate,$TenderEndDate,$BiddingStartDate,$BiddingEndDate,$BiddingRate,$DepositeType,$DepositeDetail,$TenderCategory,$NSC,$Draft,$Other,$TenderBidId;
   
   $query="select * from cw_tenders where TenderId='".$cid."'";
   $db->query($query);
   $tender_rows=$db->fetch_array();

   $TenderCategory=$tender_rows['TenderCategory'];
   
   $PurchaseAmount=$tender_rows['PurchaseAmount'];
   $Comments=$tender_rows['Comments'];

   $TenderStartDate=date('d/m/Y h A',$tender_rows['TenderStart']);
   $TenderEndDate=date('d/m/Y h A',$tender_rows['TenderEnd']);
   $BiddingStartDate=date('d/m/Y h A',$tender_rows['BiddingStart']);
   $BiddingEndDate=date('d/m/Y h A',$tender_rows['BiddingEnd']);
			  
   $bquery="select * from cw_tender_bidded where TenderId='".$cid."' and ContractorId=".$_SESSION['ContractorId']."";
   $bidded_res=$db->query($bquery);
   $bidded_rows=$db->fetch_array($bidded_res);
   $BiddingRate=$bidded_rows['BiddingRate'];
   $DepositeType=$bidded_rows['DepositeType'];
   if($DepositeType=='NSC'){
      $NSC='checked';
   }elseif($DepositeType=='Draft'){
      $Draft='checked';
   }else{
	   $Other='checked';
   }
   $DepositeDetail=$bidded_rows['DepositeDetails'];
   $TenderBidId=$bidded_rows['TenderBidId'];
   
   $query="select * from cw_estimation where EstmtId='".$tender_rows['EstmtId']."'";
    
   $db->query($query);
   if($db->num_rows())
   {
		  	  $rows=$db->fetch_array();
              $WardNo=$rows['WardNo'];
              $WorkName=$rows['WorkName'];
              $ProposerName=$rows['ProposerName'];
              $JEEmployeeCode=$rows['JEEmployeeCode'];
			  $AEEmployeeCode=$rows['AEEmployeeCode'];
	          $EXnEmployeeCode=$rows['EXnEmployeeCode'];
              $AreaName=$rows['AreaName'];
              $AddedDate=$rows['AddedDate'];
			  $AddedDate = explode('-',$AddedDate);
			  $AddedDate=$AddedDate[2]."/".$AddedDate[1]."/".$AddedDate[0]; 
			  $MadId=$rows['MadId'];
			  $MadAmtId=$rows['MadAmtId'];
			  $TotalAmount=$rows['TotalAmount'];
              $MadName=GetMad($db1,$rows['MadId']);
              $Status=$rows['Status'];
              $ItemQtys=unserialize($ItemQtys);
			  $ModifiedDate=$rows['ModifiedDate'];
	          
			  GetMadInfo($db,$MadAmtId);
			  $JEInfo=GetOfficerInfo($db,$JEEmployeeCode);
			  $AEInfo=GetOfficerInfo($db,$AEEmployeeCode);
			  $EXnInfo=GetOfficerInfo($db,$EXnEmployeeCode);
        
   }
 }

 
function GetMadInfo($db,$MadAmtId)
 {
	global $madinfoname;
	$sql="select * from cw_madamount where AmtId=".$MadAmtId."";
    $row=$db->query($sql);
     $res=$db->fetch_array($row);
     $madinfoname = $res['MadInfo'];
            
 }

function GetOfficerInfo($db,$EmployeeCode)
 {
	global $officerdetail;
	$sql="select EmployeeName,Post from employeeinfo e INNER JOIN posts p ON e.JoinningPost=p.PostId where EmployeeCode='".$EmployeeCode."'";
    $res=$db->query($sql);
    $rows = $db->fetch_array(); 
	$officerdetail = $rows['Post']." ".$rows['EmployeeName']." dksM ".$EmployeeCode;
    return $officerdetail;   
      
 }

function GetMad($db1,$MadId)
 {

	$sql="select * from cw_mads where MadId='$MadId'";
    $row=$db1->query($sql);
      $res=$db1->fetch_array($row);
      $MadName = $res['MadName'];
      return $MadName;  
    
 }


function SubmitBid( $db,$cid)
{
  
   global $PROMPT;

	extract($_POST);
	
	
	
	$insert="update cw_tender_bidded set Status='Final' where TenderBidId='$TenderBidId'";
    $db->query($insert);	 

  
   return true;
}
?>

