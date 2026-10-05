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
if($_POST['BackUrl']==''){
	$BackUrl= $_SERVER['HTTP_REFERER'];
}else{
	$BackUrl= $_POST['BackUrl'];
}

if($_POST["submit"] && $_POST["submit"]=='Edit To Tender')
{	
 		$result=EditTender( $db,$cid);
		if($result)
	    {
	      echo "<script type='text/javascript'>
        <!-- 
         window.location = 'tenders.php?msg=edit'
        //-->
        </script>";   
	    }else{
		  $PROMPT='Proposed work could not porcessed for tendering.';
		}

	
}



if(isset($_GET['msg']) && $_GET['msg']=='succ')
{
	$PROMPT='Estimate has been added successfullly.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Estimate has been edited successfullly.';
}
viewpage($db,$db1,$cid);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_tender.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$cid)
 {
   global $cid,$status,$MadId,$MadName,$TotalAmount,$WorkName,$WardNo,$AreaName,$AddedDate,$Status,$Approved,$Cancelled,$Pending,$JEInfo,$AEInfo,$EXnInfo,$ProposerName,$TS_h,$TE_h,$BS_h,$BE_h,$TenderStartDate,$TenderEndDate,$BiddingStartDate,$BiddingEndDate,$get_TST,$get_TET,$get_BST,$get_BET,$Comments,$PurchaseAmount,$selA,$selB,$selC,$selD,$selE;
   
   $query="select * from cw_tenders where TenderId='".$cid."'";
   $db->query($query);
   $tender_rows=$db->fetch_array();

   $TenderCategory=$tender_rows['TenderCategory'];
   if($TenderCategory=='A'){
     $selA='selected';
   }elseif($TenderCategory=='B'){
     $selB='selected';
   }elseif($TenderCategory=='C'){
     $selC='selected';
   }elseif($TenderCategory=='D'){
     $selD='selected';
   }else{
     $selE='selected';
   }
   $PurchaseAmount=$tender_rows['PurchaseAmount'];
   $Comments=$tender_rows['Comments'];

   
   //$AddedDate=$rows['ProposedWorkDate'];
	//$ProposedWorkDate = explode('',$AddedDate);
	//$ProposedWorkDate=$ProposedWorkDate[2]."/".$ProposedWorkDate[1]."/".$ProposedWorkDate[0]; 

   $TS_h=date('G',$tender_rows['TenderStart']);
   $TS_d=date('d',$tender_rows['TenderStart']);
   $TS_m=date('m',$tender_rows['TenderStart']);
   $TS_y=date('Y',$tender_rows['TenderStart']);
   $TenderStartDate=$TS_d."/".$TS_m."/".$TS_y; 

   $TE_h=date('G',$tender_rows['TenderEnd']);
   $TE_d=date('d',$tender_rows['TenderEnd']);
   $TE_m=date('m',$tender_rows['TenderEnd']);
   $TE_y=date('Y',$tender_rows['TenderEnd']);
   $TenderEndDate=$TE_d."/".$TE_m."/".$TE_y; 


   $BS_h=date('G',$tender_rows['BiddingStart']);
   $BS_d=date('d',$tender_rows['BiddingStart']);
   $BS_m=date('m',$tender_rows['BiddingStart']);
   $BS_y=date('Y',$tender_rows['BiddingStart']);
   $BiddingStartDate=$BS_d."/".$BS_m."/".$BS_y; 


   $BE_h=date('G',$tender_rows['BiddingEnd']);
   $BE_d=date('d',$tender_rows['BiddingEnd']);
   $BE_m=date('m',$tender_rows['BiddingEnd']);
   $BE_y=date('Y',$tender_rows['BiddingEnd']);
   $BiddingEndDate=$BE_d."/".$BE_m."/".$BE_y; 

   $get_TST=GetTimeoptions($TS_h); 
   $get_TET=GetTimeoptions($TE_h); 
   $get_BST=GetTimeoptions($BS_h); 
   $get_BET=GetTimeoptions($BE_h); 
   
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
              //$cid=$rows['EstmtId'];
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


function EditTender( $db,$cid)
{
  
   global $PROMPT;

	extract($_POST);
	
	
	$TenderStartDate = explode('/',$TenderStartDate);
	$TenderStart=mktime($TST,0,0,$TenderStartDate[1],$TenderStartDate[0],$TenderStartDate[2]);
	$TenderStartDate=$TenderStartDate[2]."-".$TenderStartDate[1]."-".$TenderStartDate[0]." ".$TST.':00:00'; 

	
	
	$TenderEndDate = explode('/',$TenderEndDate);
	$TenderEnd=mktime($TET,0,0,$TenderEndDate[1],$TenderEndDate[0],$TenderEndDate[2]);
	$TenderEndDate=$TenderEndDate[2]."-".$TenderEndDate[1]."-".$TenderEndDate[0]." ".$TET.':00:00'; 

	$BiddingStartDate = explode('/',$BiddingStartDate);
	$BiddingStart=mktime($BST,0,0,$BiddingStartDate[1],$BiddingStartDate[0],$BiddingStartDate[2]);
	$BiddingStartDate=$BiddingStartDate[2]."-".$BiddingStartDate[1]."-".$BiddingStartDate[0]." ".$BST.':00:00'; 

	$BiddingEndDate = explode('/',$BiddingEndDate);
	$BiddingEnd=mktime($BET,0,0,$BiddingEndDate[1],$BiddingEndDate[0],$BiddingEndDate[2]);
	$BiddingEndDate=$BiddingEndDate[2]."-".$BiddingEndDate[1]."-".$BiddingEndDate[0]." ".$BET.':00:00'; 
	
	$update="update cw_tenders set TenderCategory='".$TenderCategoty."',PurchaseAmount='".$PurchaseAmount."',TenderStartDate='".$TenderStartDate."',TenderEndDate='".$TenderEndDate."',BiddingStartDate='".$BiddingStartDate."',BiddingEndDate='".$BiddingEndDate."',TenderStart='".$TenderStart."',TenderEnd='".$TenderEnd."',BiddingStart='".$BiddingStart."',BiddingEnd='".$BiddingEnd."',Comments='".addslashes($Comments)."',AddedDate='".date('Y-m-d')."' where TenderId='".$cid."'";
   $db->query($update);	 

  
   return true;
}

function GetTimeoptions($hr){
    
     for($i=1;$i<=24;$i++){
		 if($i<=9){
		   $i='0'.$i;
		 }
	     if($i==$hr){
		   $strOptions.="<option value='".$i."' selected>".$i."</option>";
		 }else{
		   $strOptions.="<option value='".$i."'>".$i."</option>";
		 }
	 }
	 return $strOptions;
}

?>

