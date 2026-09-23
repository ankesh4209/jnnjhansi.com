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



GetMads($db);
GetRatelist($db);
GetJECodes($db,$db1);
GetAECodes($db,$db1);
GetEXnCodes($db,$db1);
GetCECodes($db,$db1);


$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_estimate.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


function GetMads($db)
 {
	global $madname;
	$sql="select * from cw_mads order by MadId";
    $row=$db->query($sql);
      $madname.="<select  name='MadId'  style='font-family: kruti_dev_010regular;font-size:15px;' onchange='ShowMadInfo(this.value)'>";
      $madname.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
         $MadId = $res['MadId'];
         $MadName = $res['MadName'];
        
			$madname.="<option value='$MadId' style='font-family: kruti_dev_010regular;font-size:15px;'>$MadName</option>";
		 
       }
      $madname.="</select>";
    
 }



function GetRatelist($db)
{
	global $RateList,$qty;
	$query="select * from cw_ratelist order by RateId ASC";
    $db->query($query);
	$i=0;
	$arrRateList=array(); 
	while($rows = $db->fetch_array())
	{
	  
	  $RateList.="<tr><td>".$rows['RateId']."</td><td><input type='checkbox' name = rid[] value = '".$rows['RateId']."'></td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td>".$rows['Rate']."</td><td><input type='text' name='qty-".$rows['RateId']."'  value = '' size='5'></td></tr>";
	  $i++;

	}

}

function GetJECodes($db,$db1)
 {
	global $JEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where  shreni='3' and Department='4' order by EmployeeCode ASC";
    $row=$db->query($sql);
      $JEofficers.="<select  name='JEEmployeeCode'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $JEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $JEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $JEofficers.="</select>";
    
 }

 function GetAECodes($db,$db1)
 {
	global $AEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='2' and Department='4' order by EmployeeName ASC";
    $row=$db->query($sql);
      $AEofficers.="<select  name='AEEmployeeCode'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $AEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $AEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $AEofficers.="</select>";
    
 }

 function GetEXnCodes($db,$db1)
 {
	global $EXnofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='2' and Department='4' order by EmployeeCode ASC";
    $row=$db->query($sql);
      $EXnofficers.="<select  name='EXnEmployeeCode'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $EXnofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $EXnofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $EXnofficers.="</select>";
    
 }

 function GetCECodes($db,$db1)
 {
	global $CEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='1' and Department='4' order by EmployeeCode ASC";
    $row=$db->query($sql);
      $CEofficers.="<select  name='CEofficers'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $CEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $CEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $CEofficers.="</select>";
    
 }

 
?>
