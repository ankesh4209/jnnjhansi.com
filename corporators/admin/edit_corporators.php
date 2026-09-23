<?php session_start(); ?>
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
    global $DOCUMENT_ROOT;
			extract($_POST);
	
	

		  
		  
		  $CorporatorName=addslashes($CorporatorName);
		  $RelationTag=addslashes($RelationTag);
		  $FatherName=addslashes($FatherName);
		  $Dob=explode('/',$Dob);
		  $Dob=$Dob[2]."-".$Dob[1]."-".$Dob[0];
		  $Sex=addslashes($Sex);
		  $PermanentAddress=addslashes($PermanentAddress);
		  $PresentAddress=addslashes($PresentAddress);
		  $EduQualifications=addslashes($EduQualifications);
		  $MaritialStatus=addslashes($MaritialStatus);
		  $NoOfSons=addslashes($NoOfSons);
		  $NoOfDaughters=addslashes($NoOfDaughters);
		  $Profession=addslashes($Profession);
		  $Category=addslashes($Category);
		  $Cast=addslashes($Cast);
		  $OfficeNo=addslashes($OfficeNo);
		  $MobileNo=addslashes($MobileNo);
		  $Email=addslashes($Email);
		  $PartyName=addslashes($PartyName);
		  $Designation=addslashes($Designation);
		  $WardNo=addslashes($WardNo);
		  $WardName=addslashes($WardName);
		  $Mohalla=addslashes($Mohalla);
		  $CorporatorType=addslashes($Corporator);
		  
		  $previous_record_as_corporator=addslashes($previous_record_as_corporator);
		  $other_events=addslashes($other_events);
		  $north=addslashes($north);
		  $west=addslashes($west);
		  $east=addslashes($east);
		  $south=addslashes($south);
          $hobby=addslashes($hobby);
		  
		  
          if($job_start_date!=""){
			  $job_start_date=explode('/',$job_start_date);
			  $job_start_date=$job_start_date[2]."-".$job_start_date[1]."-".$job_start_date[0];
		  }
		  if($job_end_date!=""){
			  $job_end_date=explode('/',$job_end_date);
			  $job_end_date=$job_end_date[2]."-".$job_end_date[1]."-".$job_end_date[0];
		  }
		  if($first_meeting_date!=""){
			  $first_meeting_date=explode('/',$first_meeting_date);
			  $first_meeting_date=$first_meeting_date[2]."-".$first_meeting_date[1]."-".$first_meeting_date[0];	  
		  }
		  
		  $ward_population=addslashes($ward_population);
		  $sc_population=addslashes($sc_population);
		  $sc_other_population=addslashes($sc_other_population);
		  $obc_population=addslashes($obc_population);
		  
		  $cleaner_inspector_name=addslashes($cleaner_inspector_name);
		  $cleaner_inspector_mobno=addslashes($cleaner_inspector_mobno);
		  $hawaldar_name=addslashes($hawaldar_name);
		  $hawaldar_mobno=addslashes($hawaldar_mobno);
		  
		  
		  
		  
		    $update="update corporators set 
			CorporatorType='".$CorporatorType."',
			CorporatorName='".$CorporatorName."',
			RelationTag='".$RelationTag."',
			FatherName='".$FatherName."',
			Dob='".$Dob."',
			Sex='".$Sex."',
			PermanentAddress='".$PermanentAddress."',
			PresentAddress='".$PresentAddress."',
			EduQualifications='".$EduQualifications."',
			NoOfSons='".$NoOfSons."',
			NoOfDaughters='".$NoOfDaughters."',
			Profession='".$Profession."',
			Category='".$Category."',
			Cast='".$CorporatorType."',
			CorporatorType='".$Cast."',
			PositionsHeld='".$PositionsHeld."',
			OfficeNo='".$OfficeNo."',
			MobileNo='".$MobileNo."',
			Email='".$Email."',
			PartyName='".$PartyName."',
			Designation='".$Designation."',
			WardNo='".$WardNo."',
			WardName='".$WardName."',
			Mohalla='".$Mohalla."' ,
			
			other_events='".$other_events."',
			north='".$north."',
			west='".$west."',
			east='".$east."',
			south='".$south."',
			hobby='".$hobby."',
			previous_record_as_corporator='".$previous_record_as_corporator."',
			
			job_start_date='".$job_start_date."',
			first_meeting_date='".$first_meeting_date."',
			job_end_date='".$job_end_date."',
			ward_population='".$ward_population."',
			sc_population='".$sc_population."',
			sc_other_population='".$sc_other_population."',
			obc_population='".$obc_population."',
			
			cleaner_inspector_name='".$cleaner_inspector_name."',
			cleaner_inspector_mobno='".$cleaner_inspector_mobno."',
			hawaldar_name='".$hawaldar_name."',
			hawaldar_mobno='".$hawaldar_mobno."'
			
			where CorporatorId='$CorporatorId'"; 
			//echo $update."";die;
						 
		    $db->query($update);

		  
			  if($_SERVER['SERVER_NAME']=='localhost')
			  {
				$uploadPath=$DOCUMENT_ROOT.'corporators/corp_pics/'.$_FILES['CorporatorPhoto']['name'];
				$thumbDirectory=$DOCUMENT_ROOT.'corporators/corp_pics/thumbs/';

				$uploadMapPath=$DOCUMENT_ROOT.'corporators/wardmaps/'.$_FILES['WardMap']['name'];
				$thumbMapDirectory=$DOCUMENT_ROOT.'corporators/wardmaps/thumbs/';

				
			  }
			  else
			  {
				 $uploadPath=$DOCUMENT_ROOT.'/corporators/corp_pics/'.$_FILES['CorporatorPhoto']['name'];
				 $thumbDirectory=$DOCUMENT_ROOT.'/corporators/corp_pics/thumbs/';

				$uploadMapPath=$DOCUMENT_ROOT.'/corporators/wardmaps/'.$_FILES['WardMap']['name'];
				$thumbMapDirectory=$DOCUMENT_ROOT.'/corporators/wardmaps/thumbs/';
 
			  }

		  if($_FILES['CorporatorPhoto']['name']!=''){
			   if(move_uploaded_file ($_FILES['CorporatorPhoto']['tmp_name'],$uploadPath))
			   {
					chmod("$uploadPath",0777);
			   }
			   else
			   { 
				   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
				   return false;
			   }

				$CorporatorPhoto=$_FILES['CorporatorPhoto']['name'];
			    $sql="update corporators set CorporatorPhoto='$CorporatorPhoto' where CorporatorId='$CorporatorId'";
				$db->query($sql);
			   //image should be a file path to the image you just uploaded, and moved.
				 $target_path=$uploadPath;
				 $image = $target_path;
				 $newImageName=$CorporatorPhoto;
				 $thumb = new SimpleImage();
				 $thumb->load($image);
				 $width = 132;
				 $height = 132;
				 $thumb->resize($width,$height);
				 $thumb->save($thumbDirectory . $newImageName); 
			 }
		     
			 if($_FILES['WardMap']['name']!=''){
			   
			   if(move_uploaded_file($_FILES['WardMap']['tmp_name'],$uploadMapPath))
			   {
					chmod("$uploadMapPath",0777);
			   }
			   else
			   { //echo "LLL".$_FILES['WardMap']['name'];die;
				   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
				   return false;
			   }
				$WardMap=$_FILES['WardMap']['name'];
				$sql="update corporators set WardMap='$WardMap' where CorporatorId='$CorporatorId'";
				$db->query($sql);
			   //image should be a file path to the image you just uploaded, and moved.
				 $target_path=$uploadMapPath;
				 $image = $target_path;
				 $newImageName=$WardMap;
				 $thumb = new SimpleImage();
				 $thumb->load($image);
				 $width = 150;
				 $height = 150;
				 $thumb->resize($width,$height);
				 $thumb->save($thumbMapDirectory . $newImageName); 
			 }
	
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'corporators.php?msg=e_succ'
      //-->
      </script>"; 

}


$id=$_GET['cid'];
GetCorporatorDetails($db,$id);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_corporators.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetCorporatorDetails($db,$id)
 {
    global $CorporatorId,$CorporatorName,$RelationTag,$FatherName,$Dob,$Sex,$PermanentAddress,$PresentAddress,$EduQualifications,$MaritialStatus,$NoOfSons,$NoOfDaughters,$Profession,$Category,$Cast,$PositionsHeld,$OfficeNo,$MobileNo,$Email,$PartyName,$Designation,$WardNo,$WardName,$Mohalla,$CorporatorType,$CorporatorPhoto,$C_G,$C_O,$C_SC,$C_ST,$previous_record_as_corporator,$hobby,$west,$east,$north,$south,$other_events,
$job_start_date,$first_meeting_date,$job_end_date,$ward_population,$sc_population,$sc_other_population,$obc_population,$cleaner_inspector_name	,$cleaner_inspector_mobno,$hawaldar_name,$hawaldar_mobno,$male,$female,$WardMap,$SO,$WO,$DO;

    $sql="select * from corporators where CorporatorId='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
		  $CorporatorName=stripslashes($rows['CorporatorName']);
          $RelationTag=stripslashes($rows['RelationTag']);
		  if($RelationTag=='S/O'){
			$SO='SELECTED';		  
		  }elseif($RelationTag=='D/O'){
			$DO='SELECTED';
		  }ELSEIF($RelationTag=='W/O'){
			  $WO='SELECTED';
		  }
		  $FatherName=stripslashes($rows['FatherName']);
		  $Dob=explode('-',$rows['Dob']);
		  $Dob=$Dob[2]."/".$Dob[1]."/".$Dob[0];
		  $Sex=stripslashes($rows['Sex']);
		  if($Sex=="iq#’k"){
		   $male='selected';
		  }else{
		   $female='selected';
		  }
		  $WardMap=stripslashes($rows['WardMap']);
		  $PermanentAddress=stripslashes($rows['PermanentAddress']);
		  $PresentAddress=stripslashes($rows['PresentAddress']);
		  $EduQualifications=stripslashes($rows['EduQualifications']);
		  $MaritialStatus=stripslashes($rows['MaritialStatus']);
		  $NoOfSons=stripslashes($rows['NoOfSons']);
		  $NoOfDaughters=stripslashes($rows['NoOfDaughters']);
		  $Profession=stripslashes($rows['Profession']);
		  $Category=stripslashes($rows['Category']);
		  $Cast=stripslashes($rows['Cast']);
		  $PositionsHeld=stripslashes($rows['PositionsHeld']);
		  $OfficeNo=stripslashes($rows['OfficeNo']);
		  $MobileNo=stripslashes($rows['MobileNo']);
		  $Email=stripslashes($rows['Email']);
		  $PartyName=stripslashes($rows['PartyName']);
		  $Designation=stripslashes($rows['Designation']);
		  $WardNo=stripslashes($rows['WardNo']);
		  $WardName=stripslashes($rows['WardName']);
		  $Mohalla=stripslashes($rows['Mohalla']);
		  $CorporatorType=stripslashes($rows['CorporatorType']);
		  
		  $previous_record_as_corporator=stripslashes($rows['previous_record_as_corporator']);
		  $other_events=stripslashes($rows['other_events']);
		  $north=stripslashes($rows['north']);
		  $west=stripslashes($rows['west']);
		  $east=stripslashes($rows['east']);
		  $south=stripslashes($rows['south']);
		  $hobby=stripslashes($rows['hobby']);
		  if($rows['job_start_date']!=""){
			  $job_start_date=stripslashes($rows['job_start_date']);
			  $job_start_date=explode('-',$job_start_date);
			  $job_start_date=$job_start_date[2]."/".$job_start_date[1]."/".$job_start_date[0];
		  }
		  if(trim($rows['job_end_date'])!="" && trim($rows['job_end_date'])!='0'){
			  $job_end_date=stripslashes($rows['job_end_date']);
			  $job_end_date=explode('-',$job_end_date);
			  $job_end_date=$job_end_date[2]."/".$job_end_date[1]."/".$job_end_date[0];
		  }

		  if(trim($rows['first_meeting_date'])!="" && trim($rows['first_meeting_date'])!='0'){
			  $first_meeting_date=stripslashes($rows['first_meeting_date']);
			  $first_meeting_date=explode('-',$first_meeting_date);
			  $first_meeting_date=$first_meeting_date[2]."/".$first_meeting_date[1]."/".$first_meeting_date[0];	  
		  }
		  $ward_population=stripslashes($rows['ward_population']);
		  $sc_population=stripslashes($rows['sc_population']);
		  $sc_other_population=stripslashes($rows['sc_other_population']);
		  $obc_population=stripslashes($rows['obc_population']);
		  
		  $cleaner_inspector_name=stripslashes($rows['cleaner_inspector_name']);
		  $cleaner_inspector_mobno=stripslashes($rows['cleaner_inspector_mobno']);
		  $hawaldar_name=stripslashes($rows['hawaldar_name']);
		  $hawaldar_mobno=stripslashes($rows['hawaldar_mobno']);
		  
		  
		  ViewCorpratorType($db,$CorporatorType);
		  $CorporatorPhoto=stripslashes($rows['CorporatorPhoto']);
		  

		  if($Category=='lkekU;'){
			   $C_G='selected';
		   }elseif($Category=='fiNMk oxZ'){
			   $C_O='selected';
		   }elseif($Category=='vuqlwfpr tkfr'){
			   $C_SC='selected';
		   }elseif($Category=='vuqlwfpr tutkfr'){
			   $C_ST='selected';
		   }else{
		   }

		  if($CorporatorPhoto!=''){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$CorporatorPhoto="<img src='/corporators/corp_pics/thumbs/".$CorporatorPhoto."' >";
		}
		else
		{
			$CorporatorPhoto="<img src='/corporators/corp_pics/thumbs/".$CorporatorPhoto."' >";
		}
	   
   }

   		  if($WardMap!=''){
	    if($_SERVER['SERVER_NAME']=='localhost' )
		{
			$WardMap="<img src='/corporators/wardmaps/thumbs/".$WardMap."' >";
		}
		else
		{
			$WardMap="<img src='/corporators/wardmaps/thumbs/".$WardMap."' >";
		}
	   
   }

 }

 function ViewCorpratorType($db,$CorporatorTypeId)
 {
   global $corporator;
    $sql="select * from corporatortype";
    $result=$db->query($sql);
    while($row = $db->fetch_array($result))
	  {
      $c_id=$row['CorporatorTypeId'];
      $CorporatorType=$row['CorporatorType'];
       if($c_id==$CorporatorTypeId){
	      $corporator.="<option value='$c_id' selected>$CorporatorType</option>";
	   }else{
	     $corporator.="<option value='$c_id'>$CorporatorType</option>";
	   }
	  }
 }
?>
