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

if($_POST["submit"] && $_POST["submit"]=='Update Status')
{	
 		ChangeStatus( $db,$cid);
	
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

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/estimate_details.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$cid)
 {
   global $cid,$status,$MadId,$MadName,$TotalAmount,$WorkName,$WardNo,$AreaName,$AddedDate,$Status,$Approved,$Cancelled,$Pending,$JEInfo,$AEInfo,$EXnInfo,$ProposedDate,$ProposerName , $file;
   
   $query="select * from cw_estimation where EstmtId='".$cid."'";
    
   $db->query($query);
   if($db->num_rows())
   {
		  	  $rows=$db->fetch_array();
              $WardNo=$rows['WardNo'];
              $WorkName=$rows['WorkName'];
              $JEEmployeeCode=$rows['JEEmployeeCode'];
			  $AEEmployeeCode=$rows['AEEmployeeCode'];
	          $EXnEmployeeCode=$rows['EXnEmployeeCode'];
              $AreaName=$rows['AreaName'];
			  $ProposedDate=$rows['ProposedWorkDate'];
			  $file=  $DOCUMENT_ROOT.'/media/'.$rows['file'];
	$ProposedDate = explode('-',$ProposedDate);
	$ProposedDate=$ProposedDate[2]."/".$ProposedDate[1]."/".$ProposedDate[0]; 
	$ProposerName=$rows['ProposerName'];
              $AddedDate=$rows['AddedDate'];
			  $AddedDate = explode('-',$AddedDate);
			  $AddedDate=$AddedDate[2]."/".$AddedDate[1]."/".$AddedDate[0]; 
			  $MadId=$rows['MadId'];
			  $MadAmtId=$rows['MadAmtId'];
			  $TotalAmount=$rows['TotalAmount'];
              $MadName=GetMad($db1,$rows['MadId']);
              $cid=$rows['EstmtId'];
              $Status=$rows['Status'];
              $ItemQtys=$rows['ItemQtys'];
              $ItemQtys=unserialize($ItemQtys);
			  $ModifiedDate=$rows['ModifiedDate'];
	          //GetItemsAmont($db,$ItemQtys); hide temporay	
			  if($Status=='Approved'){
			     $Approved='selected';
			  }elseif($Status=='Cancelled'){
				 $Cancelled='selected';
			  }else{
				  $Pending='selected';
			  }
			  
			  GetMadInfo($db,$MadAmtId);
			  $JEInfo=GetOfficerInfo($db,$JEEmployeeCode);
			  $AEInfo=GetOfficerInfo($db,$AEEmployeeCode);
			  $EXnInfo=GetOfficerInfo($db,$EXnEmployeeCode);
        
   }
 }

 function GetItemsAmont($db,$arrRateIds)
{
	global $RateList,$SubTotal,$TotalAmount;
	//print_r($arrRateIds);die;
	$i=1;
	$TotalAmount=0;
	$arrRateList=array(); 
	foreach($arrRateIds as $Rids=>$Qty)
	{
	  
	  $query="select * from cw_ratelist WHERE RateId='".$Rids."'";
      $db->query($query);
	  $rows = $db->fetch_array();
	  $SubTotal=$rows['Rate']*$Qty; 
	  $Mask=$rows['Rate']."&nbsp;X&nbsp;". $Qty;
	  $TotalAmount=$TotalAmount+$SubTotal;

	  $RateList.="<tr><td>".$i."</td><td>".$rows['RateId']."</td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td align='right'>".$rows['Rate']."</td><td align='right'>".$Mask."</td><td align='right'>".number_format($SubTotal,2)."</td></tr>";
	  $i++;

	}
	$RateList.="<tr><td colspan='7' align='right'><b>Total Estimated Amount&nbsp;:&nbsp;&nbsp;".number_format($TotalAmount,2)."</b></td></tr>";


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

function GetItemsDetails($db,$srtItemQtys)
{
	global $RateList,$qty;
	$query="select * from cw_ratelist order by RateId ASC";
    $db->query($query);
	$i=0;
	$arrRateList=array(); 
	$arrItemQtys=unserialize($srtItemQtys);
	foreach($arrItemQtys as $Rids=>$Qty){
		$arrRids[]=$Rids;
	}
	
	while($rows = $db->fetch_array())
	{
	  $arrItemQtys=unserialize($srtItemQtys);
	  foreach($arrItemQtys as $Rids=>$Qty){
		
		if($Rids==$rows['RateId']){
			$RateList.="<tr><td>".$rows['RateId']."</td><td><input type='checkbox' name = rid[] value = '".$rows['RateId']."' checked></td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td>".$rows['Rate']."</td><td><input type='text' name='qty-".$rows['RateId']."'  value = '$Qty' size='5'></td></tr>";
		}
	  }
	  
	  $i++;

	}

}

function ChangeStatus( $db,$cid)
{
  
   global $PROMPT;

	$id =  $cid;
    $status=$_POST['Status'];
 	$change = "update cw_estimation set Status='$status' where EstmtId in ($id)";
	$db->query($change);

	$total = $db->affected_rows();
	if($status!='Pending'){
	  $PROMPT = "Estimation has been ".$status.".";
	}else{
	  $PROMPT = "Estimation is Pending.";
	}
}
?>

