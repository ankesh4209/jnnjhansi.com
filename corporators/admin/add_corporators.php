<?php session_start(); ?>
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

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


ViewCorpratorType($db);

if($_POST['submit']!='')
 {
       $result=adddetails($db,$db1);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'add_corporators.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="Corporator no already exist.";
		  
	 }
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_corporators.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function adddetails($db,$db1)
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
		  
		  $previous_record_as_corporator=stripslashes($previous_record_as_corporator);
		  $other_events=stripslashes($other_events);
		  $north=stripslashes($north);
		  $west=stripslashes($west);
		  $east=stripslashes($east);
		  $south=stripslashes($south);
          $hobby=stripslashes($hobby);
		  
		  
		  
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
		   
		  
		  $ward_population=stripslashes($ward_population);
		  $sc_population=stripslashes($sc_population);
		  $sc_other_population=stripslashes($sc_other_population);
		  $obc_population=stripslashes($obc_population);
		  
		  $cleaner_inspector_name=stripslashes($cleaner_inspector_name);
		  $cleaner_inspector_mobno=stripslashes($cleaner_inspector_mobno);
		  $hawaldar_name=stripslashes($hawaldar_name);
		  $hawaldar_mobno=stripslashes($hawaldar_mobno);
		  

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
		     
		 $insert="insert into corporators(CorporatorType,CorporatorName,CorporatorPhoto,RelationTag,FatherName,Dob,Sex,PermanentAddress,PresentAddress,EduQualifications,MaritialStatus,NoOfSons,NoOfDaughters,Profession,Category,Cast,PositionsHeld,OfficeNo,MobileNo,Email,PartyName,Designation,WardNo,WardName,Mohalla,
		 previous_record_as_corporator,other_events,hobby,north,east,west,south,	 job_start_date,first_meeting_date,job_end_date,ward_population,sc_population,sc_other_population,obc_population,cleaner_inspector_name
		 ,cleaner_inspector_mobno,hawaldar_name,hawaldar_mobno,WardMap
		 )  
				   values('$CorporatorType','$CorporatorName','$CorporatorPhoto','$RelationTag','$FatherName','$Dob','$Sex','$PermanentAddress','$PresentAddress','$EduQualifications','$MaritialStatus','$NoOfSons','$NoOfDaughters','$Profession','$Category','$Cast','$PositionsHeld','$OfficeNo','$MobileNo','$Email','$PartyName','$Designation','$WardNo','$WardName','$Mohalla','$previous_record_as_corporator','$other_events','$hobby','$north','$east','$west','$south',
				   
'$job_start_date','$first_meeting_date','$job_end_date','$ward_population','$sc_population','$sc_other_population','$obc_population','$cleaner_inspector_name','$cleaner_inspector_mobno','$hawaldar_name','$hawaldar_mobno','$WardMap'				   
				   )";
		  $db->query($insert);
		  $sid=$db->insert_id();
		  
		 return true;
	 
 }

  function ViewCorpratorType($db)
 {
   global $corporator;
    $sql="select * from corporatortype";
    $result=$db->query($sql);
    while($row = $db->fetch_array($result))
	  {
      $c_id=$row['CorporatorTypeId'];
      $CorporatorType=$row['CorporatorType'];
      //$dname="<span style='font-family:Kruti Dev 011;font-size:18px;'>".$row['d_name']."</span>";
	    $corporator.="<option value='$c_id'>$CorporatorType</option>";
	  }
 }
?>
