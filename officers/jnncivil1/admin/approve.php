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

$TenderId=$_GET['cid'];

GetTenderDetails($db1,$TenderId);

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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/approve.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function UpdateTender($db)
 {
		  extract($_POST);
		  $sql="select TenderId from cw_tenders where EstmtId=$EstmtId";
		  $db->query($sql);
		  
		  $i=0;
		  $strTenders='';
		  while($rows = $db->fetch_array()){
            if($rows['TenderId']!=$TenderId)   
				$strTenders.=$rows['TenderId'].',';
		    $i++;
		  }

		  $strTenders=substr($strTenders,0,-1);
		  
		  $WorkStartDate = explode('/',$WorkStartDate);
	      $WorkStartDate=$WorkStartDate[2]."-".$WorkStartDate[1]."-".$WorkStartDate[0]; 

		  $WorkEndDate = explode('/',$WorkEndDate);
	      $WorkEndDate=$WorkEndDate[2]."-".$WorkEndDate[1]."-".$WorkEndDate[0]; 

		  $update1="UPDATE cw_tenders SET Status='Won',WorkOrderNo='$WorkOrderNo',WorkStartDate='$WorkStartDate',WorkEndDate='$WorkEndDate',appdate='".date('Y-m-d')."'  where TenderId='".$TenderId."'";
		  $db->query($update1);

		  $update2="UPDATE cw_tenders SET Status='Loss' where TenderId IN (".$strTenders.")";
		  $db->query($update1);
		  
		  $insert="insert into cw_progress (EstmtId,TenderId,WorkStartDate,WorkEndDate,WorkOrderNo) values('$EstmtId','$TenderId','$WorkStartDate','$WorkEndDate','$WorkOrderNo')";
		  $db->query($insert);
		  return true;
	 
 }

function GetTenderDetails($db1,$Tid){
	
	global $Status,$EstmtId,$MinRate,$TotalAmount,$WorkName,$WardNo,$ContractorName,$SubmitDate,$EstimatedAmount,$ContractorFirm,$AreaName,$TenderId,$amount;

	$sql="select t.*,e.*,t.AddedDate as SubmitDate from cw_tenders t INNER JOIN cw_estimation e ON e.EstmtId=t.EstmtId where TenderId='".$Tid."'";
	$res=$db1->query($sql);
	$rows = $db1->fetch_array($res);
	//print_r($rows);
			  $TenderId=$rows['TenderId'];
			  $EstmtId=$rows['EstmtId'];
			  $WardNo=$rows['WardNo'];
              $WorkName=$rows['WorkName'];
              $EstimatedAmount=$rows['TotalAmount'];
              $ContractorName=$rows['ContractorName'];
              $ContractorFirm=$rows['ContractorFirm'];
              $MinRate=$rows['rate'];
              $SubmitDate=$rows['SubmitDate'];
              $SubmitDate = explode('-',$SubmitDate);
			  $SubmitDate=$SubmitDate[2]."/".$SubmitDate[1]."/".$SubmitDate[0]; 
			  $amount=$rows['amount'];
			  $AreaName=$rows['AreaName'];
              
}
?>
