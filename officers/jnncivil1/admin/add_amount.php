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
       $result=addMadAmount($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'amounts.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="amount already exist.";
		  
	 }
 }

GetMads($db);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_amount.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addMadAmount($db)
 {
		  extract($_POST);
		  $MadId=$MadId;
		  $Amount=$Amount;
		  $MadInfo=addslashes($MadInfo);
		  $FinancialYear=$_POST['FinancialYear'];
		  $AddedDate=$_POST['AddedDate'];
		  $AddedDate = explode('/',$AddedDate);
	      $AddedDate=$AddedDate[2]."-".$AddedDate[1]."-".$AddedDate[0]; 
		  
		     
		  $insert="insert into cw_madamount(MadId,Amount,AddedDate,MadInfo,FinancialYear) values('$MadId','$Amount','".$AddedDate."','".$MadInfo."','".$FinancialYear."')";
		  $db->query($insert);
		  
		 return true;
	 
 }

function GetMads($db)
 {
	global $madname;
	$sql="select * from cw_mads order by MadId";
    $row=$db->query($sql);
      $madname.="<select  name='MadId'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $madname.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
         $MadId = $res['MadId'];
         $MadName = $res['MadName'];
        
			$madname.="<option value='$MadId' style='font-family: kruti_dev_010regular;font-size:15px;'>$MadName</option>";
		 
       }
      $madname.="</select>";
    
 }
?>
