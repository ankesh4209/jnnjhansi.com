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


$eid=$_GET['cid'];

GetEstimateDetails($db,$eid);

GetMads($db,$MadId);
GetRatelist($db,$srtItemQtys);
GetJECodes($db,$db1,$JEEmployeeCode);
GetAECodes($db,$db1,$AEEmployeeCode);
GetEXnCodes($db,$db1,$EXnEmployeeCode);

if(isset($_POST['submit']) && $_POST['submit']=='Update'){

     $result=UpdateEstimate($db);
       if($result)
	  {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'civilwork_photo.php?msg=e_succ'
        //-->
        </script>";   
	  }
	 
	 
}



$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_civilwork.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();



function GetEstimateDetails($db,$eid)
{
	global $MadId,$WorkName,$EmployeeId,$WardNo,$AreaName,$AddedDate,$srtItemQtys,$eid,$JEEmployeeCode,$AEEmployeeCode,$EXnEmployeeCode,$TotalAmount,$ProposerName,$ProposedDate, $completedstatus, $pendingstatus , $completedphoto,  $startphoto, $inprogressphoto , $start_photo ,$inprogess_photo ,$complete_photo;

	$sql="select * from cw_photo where id='".$eid."'";
	$res=$db->query($sql);
	$rows = $db->fetch_array($res);
	$eid=$rows['id']; 
	$WorkName=str_replace('\\','',stripslashes($rows['name_work'])); 
	$WardNo=$rows['wardno'];
	$ProposedDate=$rows['date2'];
	$ProposedDate = explode('-',$ProposedDate);
	$ProposedDate=$ProposedDate[2]."/".$ProposedDate[1]."/".$ProposedDate[0];  
	$TotalAmount=$rows['amount']; 
    $status=$rows['status']; 
	if($status==1){
	  $pendingstatus='selected="selected"';
	} else if($status==2){
	  $completedstatus='selected="selected"';
	}
 	
	$startphoto= '';
	if($rows['start_photo']!=""){
	$start_photo= $rows['start_photo']; 
	$startphoto= '<img src="../payment/'.$start_photo.'"  width="50" height="50" >';  
	}
	
	$inprogressphoto= '';
	if($rows['inprogess_photo']!=""){
	$inprogess_photo= $rows['inprogess_photo']; 
	$inprogressphoto= '<img src="../payment/'.$inprogess_photo.'"  width="50" height="50" >';  
	}
	
	$completedphoto= '';
	if($rows['complete_photo']!=""){
    $complete_photo= $rows['complete_photo']; 
	$completedphoto= '<img src="../payment/'.$complete_photo.'"  width="50" height="50" >';  
	}
			  
			  
 

}


function UpdateEstimate($db)
 {
		    //extract($_POST);
			//echo '<pre>';
		//	 print_r($_POST); 
		    $WorkName=addslashes($_POST['WorkName']); 
			$WardNo=$_POST['WardNo']; 
			$ProposedDate=$_POST['date2'];
			$ProposedDate = explode('/',$ProposedDate);
			$ProposedDate=$ProposedDate[2]."-".$ProposedDate[1]."-".$ProposedDate[0];  
			$TotalAmount=$_POST['date1']; 
	        $status=$_POST['status'];
     	    $EstmtId=$_POST['EstmtId'];
			
			$uploadPath="";
			$image_under="";
			if($_FILES['image_under']['name']!=""){
			
			
			if($_SERVER['SERVER_NAME']=='localhost')
			{
			$uploadPath=$DOCUMENT_ROOT.'jnncivil/payment/'.$_FILES['image_under']['name'];
			}
			else
			{
			// $uploadPath='../payment/'.rand(0,99).'_'.$_FILES['image_under']['name'];
			$image_under=rand(0,99).'_'.$_FILES['image_under']['name'];
			$uploadPath='../payment/'.$image_under;
			}
			
			
			if(move_uploaded_file ($_FILES['image_under']['tmp_name'],$uploadPath))
			{
			//echo "Successfully uploaded the mage";
			chmod("$uploadPath",0777);
			}
			else
			{ 
			$PROMPT= "Failed to upload file Contact Site admin1 to fix the problem";
			return false;
			}
			//$image_under=$_FILES['image_under']['name'];
			} else {
			$image_under=$_POST['underImg'];
			} 
			
			$image_completed="";
			$uploadPath="";
			if($_FILES['image_completed']['name']!=""){
			if($_SERVER['SERVER_NAME']=='localhost')
			{
			$uploadPath=$DOCUMENT_ROOT.'jnncivil/payment/'.$_FILES['image_completed']['name'];
			}
			else
			{
			$image_completed=rand(0,99).'_'.$_FILES['image_completed']['name'];
			$uploadPath='../payment/'.$image_completed;
			}
			
			if(move_uploaded_file ($_FILES['image_completed']['tmp_name'],$uploadPath))
			{
			//echo "Successfully uploaded the mage";
			chmod("$uploadPath",0777);
			}
			else
			{ 
			$PROMPT= "Failed to upload file Contact Site admin1 to fix the problem";
			return false;
			}
			
			} else {
			$image_completed=$_POST['completedImg'];
			}
			 
			$image_inprogress="";
			$uploadPath="";
			if($_FILES['image_inprogress']['name']!=""){
			if($_SERVER['SERVER_NAME']=='localhost')
			{
			$uploadPath=$DOCUMENT_ROOT.'jnncivil/payment/'.$_FILES['image_inprogress']['name'];
			}
			else
			{
			// $uploadPath='../payment/'.rand(0,99).'_'.$_FILES['image_inprogress']['name'];
			
			  $image_inprogress=rand(0,99).'_'.$_FILES['image_inprogress']['name'];
				$uploadPath='../payment/'.$image_inprogress;
			
			
			}
			
			if(move_uploaded_file ($_FILES['image_inprogress']['tmp_name'],$uploadPath))
			{
			//echo "Successfully uploaded the mage";
			chmod("$uploadPath",0777);
			}
			else
			{ 
			$PROMPT= "Failed to upload file Contact Site admin1 to fix the problem";
			return false;
			}
			//$image_inprogress=$_FILES['image_inprogress']['name'];
			} else {
			$image_inprogress=$_POST['improgressImg'];
			}

 
			    
		 	  $update="UPDATE cw_photo SET  
			name_work='".$WorkName."', 
			amount='".$TotalAmount."', 
			wardno='".$WardNo."',
			date2='".$ProposedDate."',  
			status='".$status."',
			start_photo='".$image_under."',
			inprogess_photo='".$image_inprogress."',
			complete_photo='".$image_completed."'
			WHERE id='".$EstmtId."'"; 
			$db->query($update);
		  
		 return true;
	 
 }
 
 
function GetMads($db,$Mad_Id)
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
			if($MadId==$Mad_Id){
				$madname.="<option value='$MadId' style='font-family: kruti_dev_010regular;font-size:15px;' selected>$MadName</option>";
			}else{
				$madname.="<option value='$MadId' style='font-family: kruti_dev_010regular;font-size:15px;'>$MadName</option>";
			}
       }
      $madname.="</select>";
    
 }

 function GetMadInfo($db,$Mad_Id,$MadAmtId)
 {
	global $strmadinfo;
	if($Mad_Id!='') {
	$sql="select * from cw_madamount where MadId='".$Mad_Id."'";
    $row=$db->query($sql);
     if($db->num_rows())
     {
		$strmadinfo.="<select  name='MadAmtId'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
		$strmadinfo.="<option value=''>en dk fooj.k pqus</option>";
		while($res=$db->fetch_array($row))
		{
         $MadInfo = $res['MadInfo'];
         $AmtId = $res['AmtId'];
         if($MadAmtId==$AmtId){
			 $strmadinfo.="<option value='$AmtId' style='font-family: kruti_dev_010regular;font-size:15px;' selected>$MadInfo</option>";
		 }else{
			$strmadinfo.="<option value='$AmtId' style='font-family: kruti_dev_010regular;font-size:15px;'>$MadInfo</option>";
		 }
        }
			$strmadinfo.="</select>";
     }else{
		 $strmadinfo.="<select  name='MadAmtId'  style='font-family: kruti_dev_010regular;font-size:15px;'>
                    <option value=''>en dk fooj.k pqus</option>";
      $strmadinfo.="</select>";

	 }
	
		
	}else{
		  $strmadinfo.="<select  name='MadAmtId'  style='font-family: kruti_dev_010regular;font-size:15px;'>
						<option value=''>en dk fooj.k pqus</option>";
		  $strmadinfo.="</select>";
		 
	}
    
 }

function GetRatelist($db,$srtItemQtys)
{
	global $RateList,$qty;
	$query="select * from cw_ratelist order by RateId ASC";
    $db->query($query);
	$i=0;
	$arrRateList=array(); 
	$arrItemQtys=unserialize($srtItemQtys);
	foreach($arrItemQtys as $Rids=>$Qty){
		$arrRids[]=$Rids;
	}
	
	while($rows = $db->fetch_array())
	{
	  $arrItemQtys=unserialize($srtItemQtys);
	  foreach($arrItemQtys as $Rids=>$Qty){
		
		if($Rids==$rows['RateId']){
			$RateList.="<tr><td>".$rows['RateId']."</td><td><input type='checkbox' name = rid[] value = '".$rows['RateId']."' checked></td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td>".$rows['Rate']."</td><td><input type='text' name='qty-".$rows['RateId']."'  value = '$Qty' size='5'></td></tr>";
		}
	  }
	  if(!in_array($rows['RateId'],$arrRids)){
		$RateList.="<tr><td>".$rows['RateId']."</td><td><input type='checkbox' name = rid[] value = '".$rows['RateId']."'></td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td>".$rows['Rate']."</td><td><input type='text' name='qty-".$rows['RateId']."'  value = '' size='5'></td></tr>";		  
	  }
	  $i++;

	}

}

function GetJECodes($db,$db1,$JEEmployeeCode)
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
			if($EXnEmployeeCode==$EmployeeCode){
				$EXnofficers.="<option value='$EmployeeCode' selected>$PostName $EmployeeName dksM $EmployeeCode </option>";
			}else{
				$EXnofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
			}
		 
       }
      $EXnofficers.="</select>";
    
 }

?>
