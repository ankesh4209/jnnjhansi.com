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

if(isset($_POST['submit']) && $_POST['submit']!=''){
    
	
	if(EditProposedWork($db)){
	  echo "<script type='text/javascript'>
        <!-- 
         window.location = 'proposedworks.php?msg=e_succ'
        //-->
        </script>";
	}
}

$eid=$_GET['cid'];

GetProposedWorkDetails($db,$db1,$eid);

GetJECodes($db,$db1,$JEEmployeeCode);
GetAECodes($db,$db1,$AEEmployeeCode);
GetEXnCodes($db,$db1,$EXnEmployeeCode);
GetCECodes($db,$db1,$CEEmployeeCode);


$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_proposedwork.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetProposedWorkDetails($db,$eid)
{
	global $ProposedWorkDate,$WorkName,$ProposedId,$WardNo,$ProposerName,$AddedDate,$EstimatedAmount,$eid,$JEEmployeeCode,$AEEmployeeCode,$EXnEmployeeCode,$CEEmployeeCode;

	$sql="select * from cw_proposedworks where ProposedId='".$eid."'";
	$res=$db->query($sql);
	$rows = $db->fetch_array($res);
	$ProposedId=$rows['ProposedId'];
	$WorkName=str_replace('\\','',stripslashes($rows['WorkName']));
	$EstimatedAmount=$rows['EstimatedAmount'];
	$ProposerName=str_replace('\\','',stripslashes($rows['ProposerName']));
	$JEEmployeeCode=$rows['JEEmployeeCode'];
	$AEEmployeeCode=$rows['AEEmployeeCode'];
	$EXnEmployeeCode=$rows['EXnEmployeeCode'];
	$CEEmployeeCode=$rows['CEEmployeeCode'];
	$WardNo=$rows['WardNo'];
	$AddedDate=$rows['ProposedWorkDate'];
	$ProposedWorkDate = explode('-',$AddedDate);
	$ProposedWorkDate=$ProposedWorkDate[2]."/".$ProposedWorkDate[1]."/".$ProposedWorkDate[0]; 
	
}

function EditProposedWork($db){

   extract($_POST);

   $ProposedWorkDate=explode('/',$_POST['AddedDate']);
   $ProposedWorkDate=$ProposedWorkDate['2']."-".$ProposedWorkDate['1']."-".$ProposedWorkDate['0'];
   

   $sql="update cw_proposedworks set WardNo='$WardNo',ProposerName='".addslashes($ProposerName)."',JEEmployeeCode='$JEEmployeeCode',AEEmployeeCode='$AEEmployeeCode',EXnEmployeeCode='$EXnEmployeeCode',CEEmployeeCode='$CEofficers',WorkName='".addslashes($WorkName)."',EstimatedAmount='$EstimatedAmount',ProposedWorkDate='$ProposedWorkDate' where ProposedId=$ProposedId";
	$db->query($sql);
	return 1;
}


function GetJECodes($db,$db1,$JEEmployeeCode)
 {
	global $JEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='3' and Department='4' order by EmployeeCode ASC";
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
			
			if($JEEmployeeCode==$EmployeeCode){
				$JEofficers.="<option value='$EmployeeCode' selected>$PostName $EmployeeName dksM $EmployeeCode </option>";
			}else{
				$JEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
			}
            
		 
       }
      $JEofficers.="</select>";
    
 }

 function GetAECodes($db,$db1,$AEEmployeeCode)
 {
	global $AEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='1' or shreni='2' and Department='4' order by EmployeeCode ASC";
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
			if($AEEmployeeCode==$EmployeeCode){
				$AEofficers.="<option value='$EmployeeCode' selected>$PostName $EmployeeName dksM $EmployeeCode </option>";
			}else{
				$AEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
			}
		 
       }
      $AEofficers.="</select>";
    
 }

 function GetEXnCodes($db,$db1,$EXnEmployeeCode)
 {
	global $EXnofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='1' or shreni='2' and Department='4' order by EmployeeCode ASC";
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
			if($EXnEmployeeCode==$EmployeeCode){
				$EXnofficers.="<option value='$EmployeeCode' selected>$PostName $EmployeeName dksM $EmployeeCode </option>";
			}else{
				$EXnofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
			}
		 
       }
      $EXnofficers.="</select>";
    
 }

 function GetCECodes($db,$db1,$CEEmployeeCode)
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
			if($CEEmployeeCode==$EmployeeCode){
				$CEofficers.="<option value='$EmployeeCode' selected>$PostName $EmployeeName dksM $EmployeeCode </option>";
			}else{
				$CEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
			}
            
		 
       }
      $CEofficers.="</select>";
    
 }

 
?>
