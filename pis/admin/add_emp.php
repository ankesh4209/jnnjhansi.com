<?php session_start();
error_reporting(1);
?>
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
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$class1="";
$class2="current";
$class3="";

GetDepartment($db);
GetPosts($db);

if($_POST["submit"]!='')
 {
   if(addcontent($db)){
     
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'employee.php'
        //-->
        </script>"; 
                   
   }  
                   
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_emp.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();



function addcontent($db)
 {
   global $PROMPT,$DOCUMENT_ROOT;
   $PROMPT='';
   extract($_POST);
   
   $Dob=FormatDate($Dob,'usa');
//echo '<pre>';
//print_r($_POST);
  // die;
   $sql="select * from employeeinfo where EmployeeCode='$EmployeeCode'";
   $db->query($sql);
   if ($db->num_rows())
   {	    $PROMPT = "Employee already exists.";
			$PROMPT_CLASS = "error";
			return false;
	}
	else
    {
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
			 }

			 if($_FILES['Icard']['name']!=''){
			   if(move_uploaded_file ($_FILES['Icard']['tmp_name'],$uploadIcardPath))
			   {
					chmod("$uploadIcardPath",0777);
			   }

			   $Icard=$_FILES['Icard']['name'];
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
			  
			    
			 $query="insert into employeeinfo(EmployeeCode,Board,EmployeeName,EmployeePhoto,FatherName,PermanentAdd,ResidentialAdd,PermanentThana,ResidentialThana,PermanentDist,ResidentialDist,PermanentPin,ResidentialPin,Email,Dob,BloodGroup,Sex,Religion,Category,JobSource,Cast,ServiceBook,IdentificationMark,Signature,Icard,MobileNo) values('".addslashes($EmployeeCode)."','".addslashes($Board)."','".addslashes($EmployeeName)."','".addslashes($EmployeePhoto)."','".addslashes($FatherName)."','".addslashes($PermanentAdd)."','".addslashes($ResidentialAdd)."','".addslashes($PermanentThana)."','".addslashes($ResidentialThana)."','".addslashes($PermanentDist)."','".addslashes($ResidentialDist)."','".$PermanentPin."','".$ResidentialPin."','".$Email."','".$Dob."','".$BloodGroup."','".addslashes($Sex)."','".addslashes($Religion)."','".addslashes($Category)."','".addslashes($JobSource)."','".addslashes($Cast)."','".addslashes($ServiceBook)."','".addslashes($IdentificationMark)."','".addslashes($Signature)."','".addslashes($Icard)."','".addslashes($MobileNo)."')";
			$db->query($query);
			$PROMPT = "Employee information has been added Successfully";
			$P_ID = $db->insert_id();

			return 1;
		  }
 
 }
 

function GetDepartment($db)
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
         $list_dept.="<option value='$did'>$d_name</option>";
       }
    }
 }


function GetPosts($db){
	global $catposts;
	$sql="select * from posts order by PostId ASC";
    $row=$db->query($sql);
     if($db->num_rows())
     {
      //$catposts.="<select  name='JoinningPost'  style='font-family: kruti_dev_010regular;font-size:20px;'>";
      //$catposts.="<option value=''>Select Post</option>";
	   while($res=$db->fetch_array($row))
       {
         $PostId = $res['PostId'];
         $Post = $res['Post'];
         $catposts.="<option value='$PostId' style='font-family: kruti_dev_010regular;font-size:20px;'>$Post</option>";
       }
      // $catposts.="</select>";
     } 
}


?>