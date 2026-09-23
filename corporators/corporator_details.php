<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$cid=$_GET['cid'];
GetCorporatorDetails($db,$cid);

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/corporator_details.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();



function GetCorporatorDetails($db,$cid)
 {
    global $CorporatorId,$CorporatorName,$RelationTag,$FatherName,$Dob,$Sex,$PermanentAddress,$PresentAddress,$EduQualifications,$MaritialStatus,$NoOfSons,$NoOfDaughters,$Profession,$Category,$Cast,$PositionsHeld,$OfficeNo,$MobileNo,$Email,$PartyName,$Designation,$WardNo,$WardName,$Mohalla,$CorporatorType,$CorporatorPhoto,$hobby,$west,$east,$north,$south,$other_events,
$job_start_date,$first_meeting_date,$job_end_date,$ward_population,$sc_population,$sc_other_population,$obc_population,$cleaner_inspector_name	,$cleaner_inspector_mobno,$hawaldar_name,$hawaldar_mobno,$male,$female,$WardMap,$ReportLink;
    
    $sql="select * from corporators where CorporatorId='$cid' order by WardNo ASC";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
		  $CorporatorName=stripslashes($rows['CorporatorName']);
          $RelationTag=stripslashes($rows['RelationTag']);
		  $FatherName=stripslashes($rows['FatherName']);
		  $Dob=explode('-',$rows['Dob']);
		  $Dob=$Dob[2]."/".$Dob[1]."/".$Dob[0];
		  $Sex=stripslashes($rows['Sex']);
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
		  $CorporatorType=ViewCorpratorType($db,$CorporatorType);
		  $CorporatorPhoto=stripslashes($rows['CorporatorPhoto']);
		  $WardMap=$rows['WardMap'];

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
				$WardMap="<a href='/corporators/wardmaps/".$WardMap."' target='_new'><img src='/corporators/wardmaps/thumbs/".$WardMap."' ></a>";
			}
			else
			{
				$WardMap="<a href='/corporators/wardmaps/".$WardMap."' target='_new'><img src='/corporators/wardmaps/thumbs/".$WardMap."' ></a>";
			}
	   
		}

		if($WardNo!=0){
			$ReportLink="/jnncivil/ward_wise_report.php?Ward=Single&WardNo=$WardNo&PhysicalProgress=All&Periods=All&showreport=Show+Report";
		}else{
		  $ReportLink="/jnncivil/ward_wise_report.php?Ward=All&PhysicalProgress=All&Periods=All&showreport=Show+Report";
		}

 }

  function ViewCorpratorType($db,$c_type)
 {
    $sql="select * from corporatortype where CorporatorTypeId='$c_type'";
    $result=$db->query($sql);
    $row = $db->fetch_array($result);
    $row['CorporatorType'];
	return $row['CorporatorType'];
	 
 }
?>
