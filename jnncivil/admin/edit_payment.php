<?php session_start();?>
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
include("../phplib/thumbclass.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

if($_POST['submit']!="")
{
		  extract($_POST);
		  $WorkOrderNo=$WorkOrderNo;
		  $Installment=$Installment;
		  $PayDate=$_POST['PayDate'];
		  $PayDate = explode('/',$PayDate);
	      $PayDate=$PayDate[2]."-".$PayDate[1]."-".$PayDate[0]; 
		  $CheckNo=$CheckNo;
		  $CheckDate=$_POST['CheckDate'];
		  $CheckDate = explode('/',$CheckDate);
	      $CheckDate=$CheckDate[2]."-".$CheckDate[1]."-".$CheckDate[0]; 

		  $WorkStartDate = explode('/',$WorkStartDate);
	      $WorkStartDate=$WorkStartDate[2]."-".$WorkStartDate[1]."-".$WorkStartDate[0]; 

		  $appdate = explode('/',$appdate);
	      $appdate=$appdate[2]."-".$appdate[1]."-".$appdate[0]; 

		  $WorkEndDate = explode('/',$WorkEndDate);
	      $WorkEndDate=$WorkEndDate[2]."-".$WorkEndDate[1]."-".$WorkEndDate[0]; 

		  $Status=$Status;
		  $Comments=addslashes($Comments);
		  $PayId=$PayId;
		 

		  $update="update cw_payments set 
		  WorkOrderNo='$WorkOrderNo',
		  Installment='$Installment',
		  PayDate='$PayDate',
		  CheckNo='$CheckNo',
		  Comments='$Comments',
		  CheckDate='$CheckDate'
		  where PayId='".$PayId."'";
		  $db->query($update);

		  $update1="update cw_progress set 
		  Status='$Status',WorkStartDate='".$WorkStartDate."',WorkEndDate='".$WorkEndDate."'
		  where WorkOrderNo='".$WorkOrderNo."'";
		  $db->query($update1);

		  $update1="update cw_tenders set 
		  appdate='".$appdate."',WorkStartDate='".$WorkStartDate."',WorkEndDate='".$WorkEndDate."'
		  where WorkOrderNo='".$WorkOrderNo."'";
		  $db->query($update1);

		  
		     
	
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'payments.php?msg=e_succ'
      //-->
      </script>"; 

}


$id=$_GET['cid'];
GetPayDetails($db,$id,$db1);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_payment.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetPayDetails($db,$id,$db1)
 {
    global $PayId,$WorkOrderNo,$Amount,$Installment,$CheckNo,$PayDate,$CheckDate,$Status,$Pending,$InProgress,$Cancelled,$NotStarted,$Completed,$WorkName,$WardNo,$ContractorName,$WorkStartDate,$WorkEndDate,$appdate,$Comments;
    
    $sql="select pay.*,prog.TenderId,prog.EstmtId,prog.WorkStartDate,prog.WorkEndDate,estm.WorkName,estm.WardNo from cw_payments as pay inner join cw_progress as prog ON pay.WorkOrderNo=prog.WorkOrderNo inner join cw_estimation as estm ON estm.EstmtId=prog.EstmtId where pay.PayId='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
		  $WorkOrderNo=$rows['WorkOrderNo'];
		  $WorkName=$rows['WorkName'];
		  $WardNo=$rows['WardNo'];
		  $WorkStartDate=$rows['WorkStartDate'];
          $WorkStartDate = explode('-',$WorkStartDate);
		  $WorkStartDate=$WorkStartDate[2]."/".$WorkStartDate[1]."/".$WorkStartDate[0];

		  $WorkEndDate=$rows['WorkEndDate'];
          $WorkEndDate = explode('-',$WorkEndDate);
		  $WorkEndDate=$WorkEndDate[2]."/".$WorkEndDate[1]."/".$WorkEndDate[0];
		  
		  $Amount=$rows['Amount'];
		  $Installment=$rows['Installment'];
		  $CheckNo=$rows['CheckNo'];
		  $PayDate=$rows['PayDate'];
		  $PayDate = explode('-',$PayDate);
	      $PayDate=$PayDate[2]."/".$PayDate[1]."/".$PayDate[0];
		  $CheckNo=$rows['CheckNo'];
		  $CheckDate=$rows['CheckDate'];
		  $CheckDate = explode('-',$CheckDate);
	      $CheckDate=$CheckDate[2]."/".$CheckDate[1]."/".$CheckDate[0];
		  //GetWorkOrders($db,$WorkOrderNo);
		  $Amount=$rows['Amount'];
		  $PayId=$rows['PayId'];
		  $Comments=str_replace('\\','',stripslashes($rows['Comments']));
	
	      $query1="select ContractorId,appdate from cw_tenders Where TenderId='".$rows['TenderId']."'";
		  $res1=$db1->query($query1);
		  $row1 = $db1->fetch_array($res1);
		  $appdate=$row1['appdate'];
		  $appdate = explode('-',$appdate);
		  $appdate=$appdate[2]."/".$appdate[1]."/".$appdate[0];

		  $sql2="SELECT ContractorName FROM contractors Where ContractorId='".$row1['ContractorId']."'";
		  $res2=$db1->query($sql2);
		  $row2 = $db1->fetch_array();
		  $ContractorName=str_replace('\\','',stripslashes($row2['ContractorName']));  
		  


	$sql1="select Status from cw_progress where WorkOrderNo='$WorkOrderNo'";
    $res1=$db->query($sql1);
    $rows1 = $db->fetch_array($res1);
	$Status=$rows1['Status'];
    if($Status=='Pending'){
		$Pending='selected';
	}elseif($Status=='In Progress'){
		$InProgress='selected';
	}elseif($Status=='Cancelled'){
		$Cancelled='selected';
	}elseif($Status=='Not Started'){
		$NotStarted='selected';
	}elseif($Status=='Completed'){
		$Completed='selected';
	}else{
	}
		  
 }

function GetWorkOrders($db,$wOrderNo)
 {
	global $WorkOrders;
	$sql="select * from cw_progress order by ProgId";
    $row=$db->query($sql);
      $WorkOrders.="<select  name='WorkOrderNo' >";
      $WorkOrders.="<option value=''>Select Work Order No</option>";
	  while($res=$db->fetch_array($row))
       {
         $WorkOrderNo = $res['WorkOrderNo'];
         if($wOrderNo==$WorkOrderNo){
			$WorkOrders.="<option value='$WorkOrderNo' selected>$WorkOrderNo</option>";
		 }else{
			$WorkOrders.="<option value='$WorkOrderNo' >$WorkOrderNo</option>";
		 }
       }
      $WorkOrders.="</select>";
    
 }
?>
