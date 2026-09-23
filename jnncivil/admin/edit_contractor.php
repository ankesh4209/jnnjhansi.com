<?php session_start();

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
		  $ContractorName=addslashes($ContractorName);
		  $ContractorId=$ContractorId;

		  $update="update contractors set 
			ContractorName='".$ContractorName."'
			where ContractorId='$ContractorId'"; 
			
						  
				  $db->query($update);

		  
		     
	
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'contractors.php?msg=e_succ'
      //-->
      </script>"; 

}


$id=$_GET['cid'];
GetContractorDetails($db,$id);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_contractor.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetContractorDetails($db,$id)
 {
    global $ContractorId,$ContractorName;
    
    $sql="select * from contractors where ContractorId='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
		  $ContractorName=str_replace('\\','',stripslashes($rows['ContractorName']));
		  $ContractorId=$rows['ContractorId'];
 }


?>
