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
		  $MadId=addslashes($MadId);
		  $Amount=addslashes($Amount);
		  $AddedDate = explode('/',$AddedDate);
		  $AddedDate=$AddedDate[2]."-".$AddedDate[1]."-".$AddedDate[0]; 

		  $update="update cw_madamount set 
			MadId='".$MadId."',
			MadInfo='".addslashes($MadInfo)."',
			FinancialYear='".$FinancialYear."',
			Amount='".$Amount."',
			AddedDate='".$AddedDate."'
			where AmtId='$AmtId'"; 
			
						  
				  $db->query($update);

		  
		     
	
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'amounts.php?msg=e_succ'
      //-->
      </script>"; 

}


$id=$_GET['cid'];
GetMadAmtDetails($db,$id);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_amount.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetMadAmtDetails($db,$id)
 {
    global $MadId,$Amount,$AmtId,$AddedDate,$MadInfo,$FinancialYear,$F1,$F2,$F3,$F4,$F5;
    
    $sql="select * from cw_madamount where AmtId='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
		  $MadInfo=stripslashes($rows['MadInfo']);
		  $FinancialYear=$rows['FinancialYear'];
		  $Amount=$rows['Amount'];
		  $AmtId=$rows['AmtId'];
		  $AddedDate=$rows['AddedDate'];
	      $AddedDate = explode('-',$AddedDate);
	      $AddedDate=$AddedDate[2]."/".$AddedDate[1]."/".$AddedDate[0]; 
		  GetMads($db,$rows['MadId']);
		  if($FinancialYear=='2012-2013'){
			$F1='selected';
		  }elseif($FinancialYear=='2013-2014'){
			$F2='selected';
		  }elseif($FinancialYear=='2014-2015'){
			$F3='selected';
		  }elseif($FinancialYear=='2015-2016'){
			$F4='selected';
		  }elseif($FinancialYear=='2018-2017'){
			$F5='selected';
		  }

		  
          
		  
 }

function GetMads($db,$mad_id)
 {
	global $madname;
	$sql="select * from cw_mads order by MadId";
    $row=$db->query($sql);
      $madname.="<select  name='MadId'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $madname.="<option value=''>pqu</option>";
	  while($res=$db->fetch_array($row))
       {
         $MadId = $res['MadId'];
         $MadName = $res['MadName'];
         if($mad_id==$MadId){
			$madname.="<option value='$MadId' style='font-family: kruti_dev_010regular;font-size:15px;' selected>$MadName</option>";
		 }else{
			$madname.="<option value='$MadId' style='font-family: kruti_dev_010regular;font-size:15px;'>$MadName</option>";
		 }
       }
      $madname.="</select>";
    
 }
?>
