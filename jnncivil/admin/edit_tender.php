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



if($_POST['submit']!='')
 {
       $result=editTender($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'tenders.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="tender already exist.";
		  
	 }
 }

$cid=$_GET['cid'];
GetTenderDetails($cid);
GetWorks($db,$EstmtId);
GetContractors($db,$ContractorId);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_tender.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetTenderDetails($id){

  global $db,$EstmtId,$ContractorId,$rate,$amount,$TenderId;

	  $query="select * from cw_tenders where TenderId=$id";
	  $db->query($query);
	  $rows = $db->fetch_array();
	  $EstmtId=$rows['EstmtId'];
	  $ContractorId=$rows['ContractorId'];
	  $rate=$rows['rate'];
	  $amount=$rows['amount'];
	  $TenderId=$rows['TenderId'];
}


function editTender($db)
 {
		  extract($_POST);
		  
		     
		  $update="update cw_tenders set EstmtId='".$EstmtId."',Amount='".$Amount."',ContractorId='".$ContractorId."' where  TenderId=".$TenderId."'";
		  $db->query($update);
		  
		 return true;
	 
 }

function GetWorks($db,$EstId)
 {
	global $Workname;
	$sql="select * from cw_estimation where Status='Approved' order by WardNo ASC";
    $row=$db->query($sql);
      $Workname.="<select  name='EstmtId'  style='font-family: kruti_dev_010regular;font-size:15px;width:225px;'>";
      $Workname.="<option value=''>dk;Z pqus</option>";
	  while($res=$db->fetch_array($row))
       {
         $EstmtId = $res['EstmtId'];
         $WorkName = $res['WorkName'];
         $WardNo = $res['WardNo'];
         if($EstmtId==$EstId){
		   $Workname.="<option value='$EstmtId' style='font-family: kruti_dev_010regular;font-size:15px;' selected>$WorkName &nbsp; okMZ Uka0 $WardNo</option>";
		 }else{
			$Workname.="<option value='$EstmtId' style='font-family: kruti_dev_010regular;font-size:15px;'>$WorkName &nbsp; okMZ Uka0 $WardNo</option>";
		 }
		 
       }
      $Workname.="</select>";
    
 }

 function GetContractors($db,$ContId)
 {
	global $ContractorList;
	$sql="select * from contractors order by ContractorId ASC";
    $row=$db->query($sql);
      $ContractorList.="<select  name='ContractorId'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $ContractorList.="<option value=''>Bsdsnkj pqus</option>";
	  while($res=$db->fetch_array($row))
       {
         $ContractorId = $res['ContractorId'];
         $ContractorName = $res['ContractorName'];
         $ContractorContact = $res['ContractorContact'];
         if($ContractorId==$ContId){
			$ContractorList.="<option value='$ContractorId' style='font-family: kruti_dev_010regular;font-size:15px;' selected>$ContractorId $ContractorName</option>";
		 }else{
			$ContractorList.="<option value='$ContractorId' style='font-family: kruti_dev_010regular;font-size:15px;'>$ContractorId $ContractorName</option>";
		 }
       }
      $ContractorList.="</select>";
    
 }
?>
