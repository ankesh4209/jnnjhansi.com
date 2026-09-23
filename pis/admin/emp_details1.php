<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: index.php"); 				
	  exit;
  }
?>
<link rel="stylesheet" type="text/css" href="../css/admin.css" />
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

 $emp_code=$_GET['emp_code'];
   
  
  getcontent($db,$emp_code);


function  getcontent($db,$emp_code)
 {
   global $pid,$emp_code,$EmployeeCode,$Board,$FirstName,$MiddleName,$LastName,$EmployeeName,$EmployeePhoto,$FatherName,$PermanentAdd,$ResidentialAdd,$PermanentThana,$ResidentialThana,$PermanentDist,$ResidentialDist,$PermanentPin,$ResidentialPin,$Email,$Dob,$BloodGroup,$Sex,$Religion,$Category,$JobSource,$Cast,$HusWifeName,$InJob,$HusWifeDept,$JobType,$ReservedCategory,$JoinningDate,$JoinningOfficer,$JoinningOrderNo,$JoinningOrderDate,$JoinningPost,$vinimitiDate,$Department,$PermanentPost,$PermanentDate,$samvarg,$EmployeeNature,$nidesalaya,$SeniorityNo,$shreni,$ForeignService,$Salary,$TechnicalCat,$BankName,$CurrentBasicPay,$PayIncreamentMonth,$BankACNo,$GPF,$PAN,$DateOfRetirement,$GPFStartDate,$PensionStartDate,$PensionACNo,$LocalNikay,$Education,$Year,$University,$OtherEduInfo,$TransferPost,$TransferTo,$TransferDate,$PromotionPost,$PromotionOrderNo,$PromotionDate,$VinimitiOrderNo,$FirstSelection,$FirstPromotion,$SecondPromotion,$Signature,$IdentificationMark,$ServiceBook,$ServiceStartDate,$MaratialStatus,$samvarg;
   global $S_Male,$S_Female,$C_G,$C_O,$C_SC,$C_ST,$JS_N,$JS_Ni,$JS_P,$JS_S,$JS_D;
   
   $query="select * from employeeinfo where EmployeeCode='$emp_code'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $pid=$rows['EmployeeId'];
   $EmployeeCode=stripslashes($rows['EmployeeCode']);
   $Board=stripslashes($rows['Board']);
   $FirstName=stripslashes($rows['FirstName']);
   $MiddleName=stripslashes($rows['MiddleName']);
   $LastName=stripslashes($rows['LastName']);
   $EmployeeName=stripslashes($rows['EmployeeName']);
   $EmployeePhoto=stripslashes($rows['EmployeePhoto']);
   $FatherName=stripslashes($rows['FatherName']);
   $PermanentAdd=stripslashes($rows['PermanentAdd']);
   $ResidentialAdd=stripslashes($rows['ResidentialAdd']);
   $PermanentThana=stripslashes($rows['PermanentThana']);
   $ResidentialThana=stripslashes($rows['ResidentialThana']);
   $PermanentDist=stripslashes($rows['PermanentDist']);
   $ResidentialDist=stripslashes($rows['ResidentialDist']);
   $PermanentPin=stripslashes($rows['PermanentPin']);
   $ResidentialPin=stripslashes($rows['ResidentialPin']);
   $Email=stripslashes($rows['Email']);
   $Dob=stripslashes($rows['Dob']);
   $BloodGroup=stripslashes($rows['BloodGroup']);
   $Sex=stripslashes($rows['Sex']);
   $Religion=stripslashes($rows['Religion']);
   $Category=stripslashes($rows['Category']);
   $JobSource=stripslashes($rows['JobSource']);
   $Cast=stripslashes($rows['Cast']);
   $HusWifeName=stripslashes($rows['HusWifeName']);
   $HusWifeDept=stripslashes($rows['HusWifeDept']);
   $InJob=stripslashes($rows['InJob']);
   $JobType=stripslashes($rows['JobType']);
   $ReservedCategory=stripslashes($rows['ReservedCategory']);
   $JoinningDate=stripslashes($rows['JoinningDate']);
   $JoinningOrderNo=stripslashes($rows['JoinningOrderNo']);
   $JoinningPost=stripslashes($rows['JoinningPost']);
   $JoinningPost=GetPost($db,$rows['JoinningPost']);

   $vinimitiDate=stripslashes($rows['vinimitiDate']);
   $Department=stripslashes($rows['Department']);
   $PermanentDate=stripslashes($rows['PermanentDate']);
   $samvarg=stripslashes($rows['samvarg']);
   $JoinningOfficer=stripslashes($rows['JoinningOfficer']);
   $JoinningOrderDate=stripslashes($rows['JoinningOrderDate']);
   $PermanentPost=stripslashes($rows['PermanentPost']);
   $EmployeeNature=stripslashes($rows['EmployeeNature']);
   $nidesalaya=stripslashes($rows['nidesalaya']);
   $SeniorityNo=stripslashes($rows['SeniorityNo']);
   $shreni=stripslashes($rows['shreni']);
   $Salary=stripslashes($rows['E_Salary']);
   $TechnicalCat=stripslashes($rows['TechnicalCat']);
   $TechnicalCat=stripslashes($rows['TechnicalCat']);
   $CurrentBasicPay=stripslashes($rows['CurrentBasicPay']);
   $PayIncreamentMonth=stripslashes($rows['PayIncreamentMonth']);
   $BankName=stripslashes($rows['BankName']);
   $BankACNo=stripslashes($rows['BankACNo']);
   $GPF=stripslashes($rows['GPF']);
   $PAN=stripslashes($rows['PAN']);
   $DateOfRetirement=stripslashes($rows['DateOfRetirement']);
   $GPFStartDate=stripslashes($rows['GPFStartDate']);
   $PensionStartDate=stripslashes($rows['PensionStartDate']);
   $PensionACNo=stripslashes($rows['PensionACNo']);
   $LocalNikay=stripslashes($rows['LocalNikay']);
   $Education=stripslashes($rows['Education']);
   $Year=stripslashes($rows['Year']);
   $University=stripslashes($rows['University']);
   $OtherEduInfo=stripslashes($rows['OtherEduInfo']);
   GetDepartment($db);

   
   $Dob=FormatDate($Dob);
   $JoinningDate=FormatDate($JoinningDate);
   $JoinningOrderDate=FormatDate($JoinningOrderDate);
   $vinimitiDate=FormatDate($vinimitiDate);
   $PermanentDate=FormatDate($PermanentDate);
   $DateOfRetirement=FormatDate($DateOfRetirement);
   $GPFStartDate=FormatDate($GPFStartDate);
   $PensionStartDate=FormatDate($PensionStartDate);
   
   $HusWifeDept=Department($db,$HusWifeDept);
   $Department=Department($db,$Department);
   
   $TransferPost=stripslashes($rows['TransferPost']);
   $TransferTo=stripslashes($rows['TransferTo']);
   $TransferDate=FormatDate($rows['TransferDate']);

	$PromotionPost=stripslashes($rows['PromotionPost']);
	$PromotionOrderNo=stripslashes($rows['PromotionOrderNo']);
	$PromotionDate=stripslashes($rows['PromotionDate']);
	$VinimitiOrderNo=stripslashes($rows['VinimitiOrderNo']);
	$FirstSelection=stripslashes($rows['FirstSelection']);
	$FirstPromotion=stripslashes($rows['FirstPromotion']);
	$SecondPromotion=stripslashes($rows['SecondPromotion']);

	$ServiceStartDate=FormatDate($rows['ServiceStartDate']);
	$ServiceBook=$rows['ServiceBook'];
	$Signature=stripslashes($rows['Signature']);
	$IdentificationMark=stripslashes($rows['IdentificationMark']);
	$MaratialStatus=stripslashes($rows['MaratialStatus']);
    $samvarg=GetSamvarg($db,$SID);
	

   if($EmployeePhoto!=''){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$EmployeePhoto="<img src='/pis/emp_pics/thumbs/".$EmployeePhoto."' >";
		}
		else
		{
			$EmployeePhoto="<img src='/pis/emp_pics/thumbs/".$EmployeePhoto."' >";
		}
	   
   }

   if($ServiceBook!=''){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$ServiceBook="<a href='/pis/servicebook/".$ServiceBook."' target='_new'> ".$ServiceBook."</a>";
		}
		else
		{
			$ServiceBook="<a href='/pis/servicebook/".$ServiceBook."' target='_new'> ".$ServiceBook."</a>";
		}
	   
   }

   if($Signature!=''){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$Signature="<img src='/pis/s_images/thumbs/".$Signature."' >";
		}
		else
		{
			$Signature="<img src='/pis/s_images/thumbs/".$Signature."' >";
		}
	   
   }

} 

function GetSamvarg($db,$SID){
	global $samvarg;
	$sql="select * from samvarg order by SamvargId='$SID'";
    $row=$db->query($sql);
    $res=$db->fetch_array($row);
    $samvarg = $res['SamvargName'];
	return $samvarg; 
}


function Department($db,$deptid)
 {	
	global $d_name,$db;
  
    $sql="select * from department where DeptId='$deptid'";
    $row=$db->query($sql);
	$res=$db->fetch_array($row);
    $d_name = $res['DeptName'];
	return $d_name;
}

function GetPost($db,$PostId)
 {	
	global $postname,$db;
  
    $sql="select * from posts where PostId='$PostId'";
    $row=$db->query($sql);
	$res=$db->fetch_array($row);
    $postname = $res['Post'];
	return $postname;
}

function GetDepartment($db,$deptid)
 {	
	global $list_dept;
  
    $sql="select * from department order by DeptName asc";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $did= $res['DeptId'];
         $d_name = $res['DeptName'];
		 if($Department==$did){
		    $list_dept.="<option value='$did' selected>$d_name</option>";
		 }else{
			$list_dept.="<option value='$did'>$d_name</option>";
		 }
       }
    }
 }



?>
<head><title>Personal Information System</title></head>
<body style='color:#000000;'>
<div id="main_container">
<table width="100%" align="left" border='0'>
	<tr>
	  <td><table width="100%"  border='1' cellspacing="0" cellpadding="5" style='background-color:#FFFFFF;'>
	      <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;"><b><u>O;fDrxr lwpuk</u></b></td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >deZpkjh dksM uEcj</span>&nbsp:&nbsp;<span style='padding-left:60px;'><?=$EmployeeCode?> </td>
		  <td width='50%' ><span style="font-family: kruti_dev_010regular;font-size:20px;">fudk;</span>&nbsp;:&nbsp;<span style='padding-left:140px;font-family: kruti_dev_010regular;font-size:20px;'><?=$Board?> </td>
		 </tr>
		  <!-- tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >igyk uke</span>:&nbsp;<span style='padding-left:120px;font-family: kruti_dev_010regular;font-size:20px;'> <?=$FirstName?></td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >vkf[kj dk uke</span>:&nbsp;<span style='padding-left:85px;font-family: kruti_dev_010regular;font-size:20px;'>: <?=$MiddleName?></span></td>
		 </tr>
		 <tr>
		  <td colspan='2' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >vkf[kj dk uke</span>:&nbsp;<span style='padding-left:90px;font-family: kruti_dev_010regular;font-size:20px;'>:<?=$LastName?></span> </td>
		  </tr> --> 
		  <tr>
		  <td colspan='2' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >deZpkjh dk uke </span>:&nbsp;<span style='padding-left:75px;font-family: kruti_dev_010regular;font-size:20px;'>:<?=$EmployeeName?></span> </td>
		  </tr>
		  <tr>
		  <td colspan='2' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >firk@ifr dk uke</span>:&nbsp;<span style='padding-left:65px;font-family: kruti_dev_010regular;font-size:20px;'>:<?=$FatherName?> </span></td>
		  </tr>
		  <tr>
		  <td width='50%' valign='top'><span style="font-family: 'kruti_dev_010regular';font-size:20px;text-align:top;" >LFkkbZ irk </span>&nbsp;<span style='padding-left:122px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PermanentAdd?> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >orZeku irk</span>:&nbsp;<span style='padding-left:105px;font-family: kruti_dev_010regular;font-size:20px;'><?=$ResidentialAdd?> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >Fkkuk</span>&nbsp;<span style='padding-left:165px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PermanentThana?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >Fkkuk</span>:&nbsp;<span style='padding-left:150px;font-family: kruti_dev_010regular;font-size:20px;'><?=$ResidentialThana?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >x`g tuin</span>&nbsp;<span style='padding-left:125px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PermanentDist?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >orZeku tuin</span>:&nbsp;<span style='padding-left:85px;font-family: kruti_dev_010regular;font-size:20px;'><?=$ResidentialDist?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fiu dksM</span>&nbsp;<span style='padding-left:135px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PermanentPin?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fiu dksM</span>:&nbsp;<span style='padding-left:120px;font-family: kruti_dev_010regular;font-size:20px;'><?=$ResidentialPin?> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >bZ esy</span>:&nbsp;<span style='padding-left:155px;'><?=$Email?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >tUe frfFk</span>:&nbsp;<span style='padding-left:115px;'><?=$Dob?></td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >CyM xzqi</span>&nbsp;<span style='padding-left:140px;'><?=$BloodGroup?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fyax</span>&nbsp;<span style='padding-left:155px;font-family: kruti_dev_010regular;font-size:20px;'><?=$Sex?> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">/ke</span> &nbsp;<span style='padding-left:167px;font-family: kruti_dev_010regular;font-size:20px;'><?=$Religion?></span></td>
		  
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">vkj{k.k oxZ@tkfr</span> &nbsp;<span style='padding-left:55px;font-family: kruti_dev_010regular;font-size:20px;'><?=$Category?></span></td>

		  
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">orZeku lsok es vkus dk Jksr</span> &nbsp;<span style='font-family: kruti_dev_010regular;font-size:20px;'><?=$JobSource?></span></td>

		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >tkfr</span>:&nbsp;<span style='padding-left:150px;font-family: kruti_dev_010regular;font-size:20px;'><?=$Cast?> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">oSokfgd fLFkfr</span>&nbsp; <span style='font-family: kruti_dev_010regular;font-size:20px;'><?=$MaratialStatus?></span></td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >ifr@iRuh dk uke</span>:&nbsp;<span style='padding-left:65px;font-family: kruti_dev_010regular;font-size:20px;'><?=$HusWifeName?> </td>
		  
		 </tr>
		 <tr>
		 <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">D;k ifr@iRuh  'kkldh; lsok esa g</span>&nbsp; <span style='font-family: kruti_dev_010regular;font-size:20px;'><?=$InJob?></span></td>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >;fn gk° rks fdl fo“kx es</span>:&nbsp;
		   <span style='font-family: kruti_dev_010regular;font-size:20px;'><?=$HusWifeDept?></span>
		   </td>
		  </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >igpku fpUg</span>:&nbsp;<span style='padding-left:65px;font-family: kruti_dev_010regular;font-size:20px;'><?=$IdentificationMark?> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">gLrk{kj</span>&nbsp; <span style='font-family: kruti_dev_010regular;font-size:20px;'><?=$Signature?></span></td>
		 </tr>
		 <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:22px;" ><b><u>lsok lEcU/kh fooj.k</u></b></td>
		 </tr>
		 <tr>
		 <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">lsok dk izdkj</span> &nbsp;<span style='padding-left:95px;font-family: kruti_dev_010regular;font-size:20px;'><?=$JobType?></span></td>

		<td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;">vkj{k.k Js.kh</span> &nbsp;<span style='padding-left:85px;font-family: kruti_dev_010regular;font-size:20px;'><?=$ReservedCategory?></span></td>

		 
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fu;qfDr  dh frfFk</span>:&nbsp;<span style='padding-left:85px;'><?=$JoinningDate?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fu;qfDr izkf/kdkjh</span>:&nbsp;<span style='padding-left:63px;font-family: kruti_dev_010regular;font-size:20px;'><?=$JoinningOfficer?> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fu;qfDr vknsík la0</span>:&nbsp;<span style='padding-left:60px;'> <?=$JoinningOrderNo?></td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fu;qfDr vknsík frfFk</span>:&nbsp;<span style='padding-left:31px;'>: <?=$JoinningOrderDate?></td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fu;qfDr  in</span>:&nbsp;<span style='padding-left:120px;font-family: kruti_dev_010regular;font-size:20px;'><?=$JoinningPost?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fofu;fefrdj.k frfFk</span>:&nbsp;<span style='padding-left:38px;'><?=$vinimitiDate?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >foHkkx</span>&nbsp;&nbsp;<span style='padding-left:55px;font-family: kruti_dev_010regular;font-size:20px;'>
		  <?=$Department?></span>
		  </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >LFkkbZdj.k  in</span>:&nbsp;<span style='padding-left:82px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PermanentPost?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >LFkkbZdj.k frfFk</span>:&nbsp;<span style='padding-left:90px;'><?=$PermanentDate?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fu;qfDr laoxZ</span>:&nbsp;<span style='padding-left:87px;font-family: kruti_dev_010regular;font-size:20px;'><?=$samvarg?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >deZpkjh izd`fr</span>:&nbsp;<span style='padding-left:95px;font-family: kruti_dev_010regular;font-size:20px;'> <?=$EmployeeNature?></span></td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >funs'kky; deZpkjh</span>:&nbsp;<span style='padding-left:55px;font-family: kruti_dev_010regular;font-size:20px;'> <?=$nidesalaya?></span></td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >T;sBrk dzekad</span>:&nbsp;<span style='padding-left:100px;'><?=$SeniorityNo?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >Js.kh</span>:&nbsp;<span style='padding-left:145px;font-family: kruti_dev_010regular;font-size:20px;'> <?=$shreni?></span></td>
		 </tr>
		 <tr>
		  <td width='50%'  style="font-family: 'kruti_dev_010regular';font-size:20px;">D;k fons'k lsok esa gSsa &nbsp;<span style='font-family: kruti_dev_010regular;font-size:20px;'><?=$ForeignService?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >osrueku</span>:&nbsp;<span style='padding-left:120px;'><?=$Salary?> </span></td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >oxZ </br>ºrduhdh deZpkjh ds fy;sΩ</span>:&nbsp;<span style='font-family: kruti_dev_010regular;font-size:20px;'><?=$TechnicalCat?> </span></td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >cSad dk uke</span>:&nbsp;<span style='padding-left:95px;font-family: kruti_dev_010regular;font-size:20px;'><?=$BankName?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >orZeku ewy osru</span>:&nbsp;<span style='padding-left:85px;'><?=$CurrentBasicPay?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >osru o`f) dk ekg</span>:&nbsp;<span style='padding-left:55px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PayIncreamentMonth?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >cSad [kkrk la0</span>:&nbsp;<span style='padding-left:105px;'><?=$BankACNo?> </span></td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >th0ih0,Q0 [kkrk la0</span>:&nbsp;<span style='padding-left:30px;'><?=$GPF?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >vk;dj LFkkbZ [kkrk la0</span>:&nbsp;<span style='padding-left:40px;'><?=$PAN?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >lsokfuo`Rr gksus dh frfFk</span>:&nbsp;<span style='padding-left:17px;'><?=$DateOfRetirement?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >th0ih0,Q0 izkjEHk frfFk</span>:&nbsp;<span style='padding-left:35px;'><?=$GPFStartDate?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >iSa'ku ÁkjE“ frfFk</span>:&nbsp;<span style='padding-left:45px;'><?=$PensionStartDate?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >iSa'ku vdkmaV u0</span>:&nbsp;<span style='padding-left:75px;'>:<?=$PensionACNo?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >LFkkuh; fudk;</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$LocalNikay?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >lsok izkjEHk dh frfFk</span>:&nbsp;<span style='padding-left:75px;'>:<?=$ServiceStartDate?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >lfoZl cqd MksD;wsesUV</span>:&nbsp;<span ><?=$ServiceBook?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >QksVks</span>:&nbsp;<span style='padding-left:50px;'><?=$EmployeePhoto?></span> </td>
		 </tr>
		 <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;" ><b>ìkSf{kd ;ksX;rk</b></td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >'kSf{kd ;ksX;rk</span>:&nbsp;<span  style="font-family: 'kruti_dev_010regular';font-size:20px;padding-left:75px;">:<?=$Education?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >oíkZ</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$Year?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >cksMZ@;wfuoflVh</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$University?></span> </td>
		 </tr>
		 <tr>
		  <td width='30%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >vU; fooj.k </span>:</td>
		  <td width='70%'><span style='font-family: kruti_dev_010regular;font-size:20px;'><?=nl2br($OtherEduInfo)?></span> </td>
		 </tr>

		 <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;" ><b>LFkkukUrj.k fooj.k</b></td>
		 </tr>
		 <tr>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >inuke</span>:&nbsp;<span style='padding-left:75px;font-family: kruti_dev_010regular;font-size:20px;'><?=$TransferPost?></span> </td>
		  <td width='50%' ><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >LFkkuh; fudk;@vuqHkkx</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$TransferTo?></span> </td>
		 </tr>

		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >LFkkukUrj.k dk fnukad</span>:&nbsp;<span style='padding-left:80px;'><?=$TransferDate?></span> </td>
		 </tr>
		 
		 <?
		  $query="select * from childrens where EmployeeCode='$emp_code'";
		  $db->query($query);
		  if($db->num_rows())
		  {
				  
		  ?>	
		  <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;" ><b>larku fooj.k</b></td>
		 </tr>
		 <tr>
		    <td colspan='6' >
			   <table cellspacing='0' cellpadding='0' border='1' width='100%'> 
			      <tr><td width='5%'>S.No.</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>larku dk uke</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>fyax</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>tUe frfFk</td></tr>
		          <?
				$i=1;
			      while($rows = $db->fetch_array()){
				  echo "<tr><td >$i.</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['ChildName']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['ChildSex']."</td><td >".$rows['ChildDob']."</td></tr>";
				  $i++;
				  }
		          ?>		  
			   </table>
			</td>
		 </tr>
		 	<?}?>
			 <?
		  $query="select * from nominees where EmployeeCode='$emp_code'";
		  $db->query($query);
		  if($db->num_rows())
		  {
				  
		  ?>	
		  <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;" ><b>uksfeuh fooj.k</b></td>
		 </tr>
		 <tr>
		    <td colspan='6' >
			   <table cellspacing='0' cellpadding='0' border='1' width='100%'> 
			      <tr><td width='5%'>S.No.</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>uksfeuh dk uke</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>fyax</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>fjysîku</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>tUe frfFk</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>ih0,Q0 ijlsaV</td></tr>
		          <?
				$i=1;
			      while($rows = $db->fetch_array()){
				  echo "<tr><td >$i.</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['NomineeName']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['NomineeSex']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['NomineeRelation']."</td><td '>".$rows['NomineeDob']."</td><td >".$rows['NomineePF']."</td></tr>";
				  $i++;
				  }
		          ?>		  
			   </table>
			</td>
		 </tr>
		 	<?}?>

				 <?
		  $query="select * from servicedetails where EmployeeCode='$emp_code'";
		  $db->query($query);
		  if($db->num_rows())
		  {
				  
		  ?>	
		  <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;" ><b>lsok dk fooj.k</b></td>
		 </tr>
		 <tr>
		    <td colspan='6' >
			   <table cellspacing='0' cellpadding='0' border='1' width='100%'> 
			      <tr><td width='5%'>S.No.</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>inuke</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>LFkkuh; fudk;@funsîkky;</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>fnukad ls</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>fnukad rd</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>osrueku</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>ewy osru</td></tr>
		          <?
				$i=1;
			      while($rows = $db->fetch_array()){
				  echo "<tr><td >$i.</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['Post']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['S_Nikay']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['FromDate']."</td><td '>".$rows['ToDate']."</td><td >".$rows['Salary']."</td><td >".$rows['BasicSalary']."</td></tr>";
				  $i++;
				  }
		          ?>		  
			   </table>
			</td>
		 </tr>
		 	<?}?>

			<?
		  $query="select * from awards where EmployeeCode='$emp_code'";
		  $db->query($query);
		  if($db->num_rows())
		  {
				  
		  ?>	
		  <tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;" ><b>iqjLdkj fooj.k</b></td>
		 </tr>
		 <tr>
		    <td colspan='6' >
			   <table cellspacing='0' cellpadding='0' border='1' width='100%'> 
			      <tr><td width='5%'>S.No.</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>iqjLdkj dk izdkj</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>iqjLd`r djus okys vf/kdkjh dk uke</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>mnnsî;</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>frfFk</td><td style="font-family: 'kruti_dev_010regular';font-size:20px;" width='25%'>ljdkjh@futh</td></tr>
		          <?
				$i=1;
			      while($rows = $db->fetch_array()){
				  echo "<tr><td >$i.</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['AwardType']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['AwardByOfficer']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['Reason']."</td><td >".$rows['AwardDate']."</td><td style='font-family: kruti_dev_010regular;font-size:20px;'>".$rows['Type']."</td></tr>";
				  $i++;
				  }
		          ?>		  
			   </table>
			</td>
		 </tr>
		 	<?}?>
		<tr>
		 <td align="left"  class="successmsg" colspan='2' style="font-family: 'kruti_dev_010regular';font-size:20px;" ><b>inksUUkfr dk fooj.k</b></td>
		 </tr>	
		<tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >inksUufr dk in</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PromotionPost?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >inksUufr vknsîk la0@fnukad</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PromotionOrderNo?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fu;qf‰ LFkkbZ@vLFkkbZ vknsîk la0@fnukad</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$PromotionDate?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >fofu;ferdj.k vknsîk la0@fnukad</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$VinimitiOrderNo?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >igyh lsysDîku xzsM frfFk@osrueku@izkIr</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$FirstSelection?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >igyh inksUufr xzsM frfFk@osrueku@izkIr</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$FirstPromotion?></span> </td>
		 </tr>
		 <tr>
		  <td width='50%' colspan='2'><span style="font-family: 'kruti_dev_010regular';font-size:20px;" >f}rh; inksUufr xzsM frfFk@osrueku@izkIr</span>:&nbsp;<span style='padding-left:80px;font-family: kruti_dev_010regular;font-size:20px;'><?=$SecondPromotion?></span> </td>
		 </tr>
		 </table>
		  </td></tr>
	

				
 </table>
</div>
