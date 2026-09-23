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

//echo '<pre>';
//print_r($_POST);
if(isset($_POST['submit']) && $_POST['submit']=='Next')
{

	$WorkName=str_replace('\\','',stripslashes(nl2br($_POST['WorkName'])));
	$MadId=$_POST['MadId'];
	$MadAmtId=$_POST['MadAmtId'];
	$EstmtId=$_POST['EstmtId'];
	$WardNo=$_POST['WardNo'];
	$AreaName=$_POST['AreaName'];
	$estimateamt=$_POST['estimateamt'];//temporary
	$JEEmployeeCode=$_POST['JEEmployeeCode'];
	$AEEmployeeCode=$_POST['AEEmployeeCode'];
	$EXnEmployeeCode=$_POST['EXnEmployeeCode'];
	$AddedDate=$_POST['AddedDate'];
	$rateids=$_POST['rid'];
    $ProposedDate=$_POST['ProposedDate'];
	$ProposerName=addslashes($_POST['ProposerName']);

	$arrQtyRids=array();
	foreach($rateids as $Rids)
	{
	  $qty_index="qty-".$Rids;
	  $arrQtyRids[$Rids]=$_POST[$qty_index];
	  
	  
	}
	$QtyRids= urlencode(serialize($arrQtyRids));

	GetMadName($db,$MadId);
	GetMadInfo($db,$MadAmtId);
	$JEInfo=GetOfficerInfo($db,$JEEmployeeCode);
	$AEInfo=GetOfficerInfo($db,$AEEmployeeCode);
	$EXnInfo=GetOfficerInfo($db,$EXnEmployeeCode);
	GetItemsAmont($db,$rateids);

}


if(isset($_POST['submit']) && $_POST['submit']=='Update Estimate'){

     $result=UpdateEstimate($db);
       if($result)
	  {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'estimates.php?msg=e_succ'
        //-->
        </script>";   
	  }
	 
	 
}



$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_estimate_next.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function UpdateEstimate($db)
 {
		    //extract($_POST);
			//echo '<pre>';
			//print_r($_POST);die;
		    $WorkName=addslashes($_POST['WorkName']);
			$MadId=$_POST['MadId'];
			$MadAmtId=$_POST['MadAmtId'];
			$EstmtId=$_POST['EstmtId'];
			$WardNo=$_POST['WardNo'];
			$ItemQtys=urldecode($_POST['ItemQtys']);
			$AreaName=addslashes($_POST['AreaName']);
			$JEEmployeeCode=$_POST['JEEmployeeCode'];
			$AEEmployeeCode=$_POST['AEEmployeeCode'];
			$EXnEmployeeCode=$_POST['EXnEmployeeCode'];
			$ProposedDate=$_POST['ProposedDate'];
			$ProposedDate = explode('/',$ProposedDate);
			$ProposedDate=$ProposedDate[2]."-".$ProposedDate[1]."-".$ProposedDate[0]; 
	        $ProposerName=addslashes($_POST['ProposerName']);

			
			$AddedDate=$_POST['AddedDate'];
			$AddedDate = explode('/',$AddedDate);
			$AddedDate=$AddedDate[2]."-".$AddedDate[1]."-".$AddedDate[0]; 
			$rateids=$_POST['rid'];
			//when rate list dispaly use
			//$TotalAmount=$_POST['TotalAmount'];
			//else display or save
			$TotalAmount=$_POST['estimateamt'];

			
			    
		   $update="UPDATE cw_estimation SET 
		   MadId='".$MadId."',
		   MadAmtId='".$MadAmtId."',
		   WorkName='".$WorkName."',
		   ItemQtys='".$ItemQtys."',
		   TotalAmount='".$TotalAmount."',
		   JEEmployeeCode='".$JEEmployeeCode."',
		   AEEmployeeCode='".$AEEmployeeCode."',
		   EXnEmployeeCode='".$EXnEmployeeCode."',
		   WardNo='".$WardNo."',
		   ProposedWorkDate='".$ProposedDate."',
		   ProposerName='".$ProposerName."',
		   AreaName='".$AreaName."',
		   AddedDate='".$AddedDate."' WHERE EstmtId='".$EstmtId."'";
		   $db->query($update);
		  
		 return true;
	 
 }

function GetMadName($db,$MadId)
 {
	global $madname;
	$sql="select * from cw_mads where MadId=".$MadId."";
    $row=$db->query($sql);
     $res=$db->fetch_array($row);
     $madname = $res['MadName'];
            
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

function GetItemsAmont($db,$arrRateIds)
{
	global $RateList,$SubTotal,$TotalAmount;
	
	$i=1;
	$TotalAmount=0;
	$arrRateList=array(); 
	foreach($arrRateIds as $Rids)
	{
	  
	  $query="select * from cw_ratelist WHERE RateId='".$Rids."'";
      $db->query($query);
	  $rows = $db->fetch_array();
	  $qty_index="qty-".$Rids;
	  $SubTotal=$rows['Rate']*$_POST[$qty_index]; 
	  $Mask=$rows['Rate']."&nbsp;X&nbsp;". $_POST[$qty_index];
	  $TotalAmount=$TotalAmount+$SubTotal;

	  $RateList.="<tr><td>".$i."</td><td>".$rows['RateId']."</td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td align='right'>".$rows['Rate']."</td><td align='right'>".$Mask."</td><td align='right'>".number_format($SubTotal,2)."</td></tr>";
	  $i++;

	}
	$RateList.="<tr><td colspan='7' align='right'><b>Total Estimated Amount&nbsp;:&nbsp;&nbsp;".number_format($TotalAmount,2)."</b></td></tr>";


}

?>
