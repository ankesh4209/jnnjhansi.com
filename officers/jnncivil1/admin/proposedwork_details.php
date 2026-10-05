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

if($_POST["submit"] && $_POST["submit"]=='Add To Tender')
{	
 		$result=AddTender( $db,$cid,$db1);
		if($result)
	    {
	      echo "<script type='text/javascript'>
        <!-- 
         window.location = 'tenders.php?msg=succ'
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

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/proposedwork_details.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$cid)
 {
   global $cid,$status,$MadId,$MadName,$TotalAmount,$WorkName,$WardNo,$AreaName,$AddedDate,$Status,$Approved,$Cancelled,$Pending,$JEInfo,$AEInfo,$EXnInfo,$ProposerName;
   
   $query="select * from cw_estimation where EstmtId='".$cid."'";
    
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
              $cid=$rows['EstmtId'];
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


function AddTender( $db,$cid,$db1)
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
	
	$insert="insert into cw_tenders(EstmtId,TenderCategory,PurchaseAmount,Vat,TenderStartDate,TenderEndDate,BiddingStartDate,BiddingEndDate,TenderStart,TenderEnd,BiddingStart,BiddingEnd,Comments,AddedDate) values('".$cid."','".$TenderCategoty."','".$PurchaseAmount."','".$Vat."','".$TenderStartDate."','".$TenderEndDate."','".$BiddingStartDate."','".$BiddingEndDate."','".$TenderStart."','".$TenderEnd."','".$BiddingStart."','".$BiddingEnd."','".addslashes($Comments)."','".date('Y-m-d')."')";
    $db->query($insert);	 

   $update="update cw_estimation set IsTender='1' where EstmtId='$cid'";
   $db->query($update);	 

   $sql="select ContractorName,Email,Phone from contractors where hashiyatkishreni='$TenderCategoty' and Status='Approved'";
   $res=$db1->query($sql);
   $i=0;
   while($rows=$db1->fetch_array($res)){
        $ContractorName=$rows['ContractorName'];
        $Email=$rows['Email'];
        $Phone=$rows['Phone'];
		if($Email!='')
				{
					
					$message = "<html><body>";
					$message .= "<table   cellpadding='1' cellpadding='1'>";
					$message .= "<tr><td colspan='2'>Hi ".$row['ContractorName'].",</td></tr>";
					$message .= "<tr><td colspan='2'><p>&nbsp;&nbsp;&nbsp;&nbsp;New tender has been uploaded.<a href='http://www.jnnjhansi.com'>click here to check tender detail on Nagar Nigam Website</p></td></tr>";
					
					$message .= "<tr><td colspan='2'>&nbsp;</td></tr>";
					$message .= "<tr><td colspan='2'>Thanks & Regards,</td></tr>";
					$message .= "<tr><td colspan='2'>JAHANSI NAGRA NIGAM</td></tr>";
					
					$message .= "</table>";
					$message .= "</body></html>";
					$subject = 'New Tender';
					$headers = "MIME-Version: 1.0\r\n"; 
					$headers  .= "From: Nagar Nigam Jhansi<webmaster@jnnjhansi.com>\r\n";
					$headers .= "Content-type: text/html; charset=utf-8";
    
					mail($Email,$subject,$message,$headers);

				}
					
		 if($Phone!="")
		 {
		  $message="New%20tender%20has%20been%20uploaded.%20Please%20check%20Nagar%20Nagar%20Nigam%20website.";
		  //$message="test";
		  $url1="http://dndopen.dove-sms.com/TransSMS/SMSAPI.jsp?username=JNNJHS&password=JNNJHS&sendername=JNNJHS&mobileno=$Phone&message=$message";
		  $file1=fopen("$url1","r");
		  fclose("$file1");
		 }
		$i++;
   }
   return true;
}
?>

