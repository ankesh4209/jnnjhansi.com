<?php session_start();
error_reporting(1);
?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: index.php"); 				
	  exit;
  }
?>

<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/thumbclass.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$class1="";
$class2="current";
$class3="";

$emp_code=$_GET['emp_code'];

GetEmployeeDetails($db,$emp_code);

if($_POST["submit"]!='')
 {
   if(addcontent($db)){
     
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'promotiondetails.php'
        //-->
        </script>"; 
                   
   }  
                   
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_promotiondetail.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();



function addcontent($db)
 {
   global $PROMPT,$DOCUMENT_ROOT;
   $PROMPT='';
   extract($_POST);
   
   //$TransferDate=FormatDate($TransferDate,'usa');
   $sql="select * from employeeinfo where EmployeeCode='$EmployeeCode'";
   $db->query($sql);
   if ($db->num_rows())
   {	    $query="update employeeinfo set 
		   PromotionPost='".addslashes($PromotionPost)."',
		   PromotionOrderNo='".addslashes($PromotionOrderNo)."',
		   PromotionDate='".addslashes($PromotionDate)."',
		   VinimitiOrderNo='".addslashes($VinimitiOrderNo)."',
		   FirstSelection='".addslashes($FirstSelection)."',
		   FirstPromotion='".addslashes($FirstPromotion)."',
		   SecondPromotion='".addslashes($SecondPromotion)."' where EmployeeCode='$EmployeeCode'";
		   
		   //echo $query;die;
		   $db->query($query);
		   return 1;
	}
	else
    {
			  $PROMPT='Employee Code does not exists.';
	}
 
 }

 function  GetEmployeeDetails($db,$emp_code)
 {
   global $EmployeeCode,$PromotionPost,$PromotionOrderNo,$PromotionDate,$VinimitiOrderNo,$FirstSelection,$FirstPromotion,$SecondPromotion;
   
   $query="select * from employeeinfo where EmployeeCode='$emp_code'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $EmployeeCode=stripslashes($rows['EmployeeCode']);
   $PromotionPost=stripslashes($rows['PromotionPost']);
   $PromotionOrderNo=stripslashes($rows['PromotionOrderNo']);
   $PromotionDate=stripslashes($rows['PromotionDate']);
   $VinimitiOrderNo=stripslashes($rows['VinimitiOrderNo']);
   $FirstSelection=stripslashes($rows['FirstSelection']);
   $FirstPromotion=stripslashes($rows['FirstPromotion']);
   $SecondPromotion=stripslashes($rows['SecondPromotion']);
   

}
?>