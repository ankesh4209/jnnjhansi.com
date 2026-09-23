<?php session_start(); ?>
<?php	

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$cid=$_GET['cid'];
GetCorporatorDetails($db,$cid);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/corporator_details.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();


function GetCorporatorDetails($db,$cid)
 {
    global $CorporatorId,$CorporatorName,$RelationTag,$FatherName,$Dob,$Sex,$PermanentAddress,$PresentAddress,$EduQualifications,$MaritialStatus,$NoOfSons,$NoOfDaughters,$Profession,$Category,$Cast,$PositionsHeld,$OfficeNo,$MobileNo,$Email,$PartyName,$Designation,$WardNo,$WardName,$Mohalla,$CorporatorType,$CorporatorPhoto;
    
    $sql="select * from corporators where CorporatorId='$cid'";
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
