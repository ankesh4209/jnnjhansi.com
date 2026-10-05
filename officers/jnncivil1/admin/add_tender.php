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
       $result=addTender($db);
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
		 $regno_msg="amount already exist.";
		  
	 }
 }

GetWorks($db);
GetContractors($db);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_tender.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addTender($db)
 {
		  extract($_POST);
		  $AddedDate = explode('/',$AddedDate);
	      $AddedDate=$AddedDate[2]."-".$AddedDate[1]."-".$AddedDate[0]; 
		  
		     
		  $insert="insert into cw_tenders(EstmtId,Amount,AddedDate,ContractorId,Rate) values('".$EstmtId."','".$Amount."','".$AddedDate."','".$ContractorId."','".$Rate."')";
		  $db->query($insert);
		  
		 return true;
	 
 }

function GetWorks($db)
 {
	global $Workname;
	$sql="select * from cw_estimation where Status='Approved' order by EstmtId";
    $row=$db->query($sql);
      $Workname.="<select  name='EstmtId'  style='font-family: kruti_dev_010regular;font-size:15px;width:225px;'>";
      $Workname.="<option value=''>dk;Z pqus</option>";
	  while($res=$db->fetch_array($row))
       {
         $EstmtId = $res['EstmtId'];
         $WorkName = $res['WorkName'];
         $WardNo = $res['WardNo'];
        
			$Workname.="<option value='$EstmtId' style='font-family: kruti_dev_010regular;font-size:15px;'>$WorkName &nbsp; okMZ Uka0 $WardNo</option>";
		 
       }
      $Workname.="</select>";
    
 }

 function GetContractors($db)
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
         
			$ContractorList.="<option value='$ContractorId' style='font-family: kruti_dev_010regular;font-size:15px;'>$ContractorId $ContractorName</option>";
		 
       }
      $ContractorList.="</select>";
    
 }
?>
