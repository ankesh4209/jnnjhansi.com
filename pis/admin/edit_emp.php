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
include("../phplib/data.constant.php");
include("../phplib/thumbclass.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

 $emp_code=$_GET['emp_code'];
   
$class1="";
$class2="current";
$class3="";
if(isset($_GET['msg']) && $_GET['msg']=='error'){
	$PROMPT= "Failed to upload file Contact Site admin to fix the problem";
		   
}
  GetEmployeeDetails($db,$emp_code);


if($_POST["submit"]!='')
{
   $emp_code=$_REQUEST['emp_code'];
	   if(editcontent($db,$emp_code)){
		 echo "<script type='text/javascript'>
			<!-- 
			 window.location = 'employee.php'
			//-->
			</script>";
	   }else{
			echo "<script type='text/javascript'>
			<!-- 
			 window.location = 'edit_emp.php?emp_code=$emp_code&msg=error'
			//-->
			</script>";
	   }
 }

  


 

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_emp.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function   editcontent($db,$emp_code)
{
   global $PROMPT,$DOCUMENT_ROOT,$cid;
       
           extract($_POST);
   //echo '<pre>';
   //print_r($_POST);

   $Dob=FormatDate($Dob,'usa');
   
   $emp_code=$emp_code;

   
   $query.="update employeeinfo set EmployeeCode='$EmployeeCode',
   Board='".addslashes($Board)."',
   EmployeeName='".addslashes($EmployeeName)."',
   FatherName='".addslashes($FatherName)."',
   PermanentAdd='".addslashes($PermanentAdd)."',
   ResidentialAdd='".addslashes($ResidentialAdd)."',
   PermanentThana='".addslashes($PermanentThana)."',
   ResidentialThana='".addslashes($ResidentialThana)."',
   PermanentDist='".addslashes($PermanentDist)."',
   ResidentialDist='".addslashes($ResidentialDist)."',
   PermanentPin='".addslashes($PermanentPin)."',
   ResidentialPin='".addslashes($ResidentialPin)."',
   Email='".addslashes($Email)."',
   Dob='".addslashes($Dob)."',
   BloodGroup='".addslashes($BloodGroup)."',
   Sex='".addslashes($Sex)."',
   Religion='".addslashes($Religion)."',
   Category='".addslashes($Category)."',
   JobSource='".addslashes($JobSource)."',
   IdentificationMark='".addslashes($IdentificationMark)."',
   MobileNo='".addslashes($MobileNo)."',
   Cast='".addslashes($Cast)."' where EmployeeCode='$emp_code'";

   
  // echo $query;die;
   $db->query($query);
   
  
			  if($_SERVER['SERVER_NAME']=='localhost')
			  {
				$uploadPath=$DOCUMENT_ROOT.'pis/emp_pics/'.$_FILES['EmployeePhoto']['name'];
				$thumbDirectory=$DOCUMENT_ROOT.'pis/emp_pics/thumbs/';

				$uploadSPath=$DOCUMENT_ROOT.'pis/s_images/'.$_FILES['Signature']['name'];
				$thumbSDirectory=$DOCUMENT_ROOT.'pis/s_images/thumbs/';

				$uploadSbookPath=$DOCUMENT_ROOT.'pis/servicebook/'.$_FILES['ServiceBook']['name'];
				$uploadIcardPath=$DOCUMENT_ROOT.'pis/icard/'.$_FILES['Icard']['name'];
			  }
			  else
			  {
				 $uploadPath=$DOCUMENT_ROOT.'/pis/emp_pics/'.$_FILES['EmployeePhoto']['name'];
				 $thumbDirectory=$DOCUMENT_ROOT.'/pis/emp_pics/thumbs/';

				 $uploadSPath=$DOCUMENT_ROOT.'/pis/s_images/'.$_FILES['Signature']['name'];
				 $thumbSDirectory=$DOCUMENT_ROOT.'/pis/s_images/thumbs/';

				 $uploadSbookPath=$DOCUMENT_ROOT.'/pis/servicebook/'.$_FILES['ServiceBook']['name'];
				 $uploadIcardPath=$DOCUMENT_ROOT.'/pis/icard/'.$_FILES['Icard']['name'];
			  }
			 
			 if($_FILES['ServiceBook']['name']!=''){
			   if(move_uploaded_file ($_FILES['ServiceBook']['tmp_name'],$uploadSbookPath))
			   {
					chmod("$uploadSbookPath",0777);
			   }

			   
			    $ServiceBook=$_FILES['ServiceBook']['name'];
			    $sql="update employeeinfo set ServiceBook='$ServiceBook' where EmployeeCode='$emp_code'";
				$db->query($sql);
			 }

			 if($_FILES['Icard']['name']!=''){
			   if(move_uploaded_file ($_FILES['Icard']['tmp_name'],$uploadIcardPath))
			   {
					chmod("$uploadIcardPath",0777);
			   }

			   
				$Icard=$_FILES['Icard']['name'];
			    $sql="update employeeinfo set Icard='$Icard' where EmployeeCode='$emp_code'";
				$db->query($sql);
			 }
			 
			 if($_FILES['EmployeePhoto']['name']!=''){
			   if(move_uploaded_file ($_FILES['EmployeePhoto']['tmp_name'],$uploadPath))
			   {
					chmod("$uploadPath",0777);
			   }
			   else
			   { 
				   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
				   return false;
			   }
				$EmployeePhoto=$_FILES['EmployeePhoto']['name'];
			    $sql="update employeeinfo set EmployeePhoto='$EmployeePhoto' where EmployeeCode='$emp_code'";
				$db->query($sql);
				   //image should be a file path to the image you just uploaded, and moved.
				 $target_path=$uploadPath;
				 $image = $target_path;
				 $newImageName=$EmployeePhoto;
				 $thumb = new SimpleImage();
				 $thumb->load($image);
				 $width = 132;
				 $height = 132;
				 $thumb->resize($width,$height);
				 $thumb->save($thumbDirectory . $newImageName); 
			 }

			 if($_FILES['Signature']['name']!=''){
			   if(move_uploaded_file ($_FILES['Signature']['tmp_name'],$uploadSPath))
			   {
					chmod("$uploadSPath",0777);
			   }
			   else
			   { 
				   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
				   return false;
			   }

				$Signature=$_FILES['Signature']['name'];
			    $sql="update employeeinfo set Signature='$Signature' where EmployeeCode='$emp_code'";
				$db->query($sql);
			   //image should be a file path to the image you just uploaded, and moved.
				 $target_spath=$uploadSPath;
				 $simage = $target_spath;
				 $newSImageName=$Signature;
				 $sthumb = new SimpleImage();
				 $sthumb->load($simage);
				 $width = 132;
				 $height = 132;
				 $sthumb->resize($width,$height);
				 $sthumb->save($thumbSDirectory . $newSImageName); 
			 }


                
   return true;
 
 
 }
 
function  GetEmployeeDetails($db,$emp_code)
 {
   global $emp_code,$EmployeeCode,$Board,$FirstName,$MiddleName,$LastName,$EmployeeName,$EmployeePhoto,$FatherName,$PermanentAdd,$ResidentialAdd,$PermanentThana,$ResidentialThana,$PermanentDist,$ResidentialDist,$PermanentPin,$ResidentialPin,$Email,$Dob,$BloodGroup,$Sex,$Religion,$Category,$JobSource,$Cast,$IdentificationMark,$Signature,$ServiceBook,$Icard,$MobileNo;
   global $S_Male,$S_Female,$C_G,$C_O,$C_SC,$C_ST,$JS_N,$JS_Ni,$JS_P,$JS_MD,$JS_D,$IJ_Y,$IJ_N,$R_H,$R_M,$R_S,$R_I,$DOCUMENT_ROOT;
   
   $query="select * from employeeinfo where EmployeeCode='$emp_code'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $emp_code=$emp_code;
   $EmployeeCode=stripslashes($rows['EmployeeCode']);
   $Board=stripslashes($rows['Board']);
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
   $IdentificationMark=stripslashes($rows['IdentificationMark']);
   $Signature=stripslashes($rows['Signature']);
   $ServiceBook=stripslashes($rows['ServiceBook']);
   $Icard=stripslashes($rows['Icard']);
   $MobileNo=$rows['MobileNo'];
   
   $Dob=FormatDate($Dob);
   if($Sex=='efgyk'){
       $S_Female='selected';
   }else{
      $S_Male='selected';
   }

   if($Religion=='fgUnw'){
       $R_H='selected';
   }elseif($Religion=='eqfLye'){
       $R_M='selected';
   }elseif($Religion=='flD[k'){
       $R_S='selected';
   }elseif($Religion=='bZlkbZ'){
       $R_I='selected';
   }else{
   }

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

   if($JobSource=='lh/kh HkrhZ'){
       $JS_D='selected';
   }elseif($JobSource=='izksUufr'){
       $JS_P='selected';
   }elseif($JobSource=='e`rd vkfJr'){
       $JS_MD='selected';
   }else{
   }
   
   
   
   if($EmployeePhoto!='' && file_exists($DOCUMENT_ROOT."/pis/emp_pics/thumbs/".$EmployeePhoto)){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$EmployeePhoto="<img src='/pis/emp_pics/thumbs/".$EmployeePhoto."' >";
		}
		else
		{
			$EmployeePhoto="<img src='/pis/emp_pics/thumbs/".$EmployeePhoto."' >";
		}
	   
   }

   if($Signature!='' && file_exists($DOCUMENT_ROOT."/pis/s_images/thumbs/".$Signature)){
	    if($_SERVER['SERVER_NAME']=='localhost' || $_SERVER['SERVER_NAME']=='cropsoft.com')
		{
			$Signature="<img src='/pis/s_images/thumbs/".$Signature."' >";
		}
		else
		{
			$Signature="<img src='/pis/s_images/thumbs/".$Signature."' >";
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
}

function GetDepartment($db,$deptid)
 {	
	global $list_dept;
    
	$list_dept='';
    $sql="select * from department order by DeptName asc";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $did= $res['DeptId'];
         $d_name = $res['DeptName'];
		 if($deptid==$did){
		    $list_dept.="<option value='$did' selected>$d_name</option>";
		 }else{
			$list_dept.="<option value='$did'>$d_name</option>";
		 }
       }
    }
	return $list_dept;
 }

?>