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

 $cid=$_GET['cid'];
   
  
  GetWorkInfo($db,$cid);


if($_POST["submit"]!='')
 {
   $cid=$_REQUEST['cid'];
   editWork($db,$cid);
     echo "<script type='text/javascript'>
        <!-- 
         window.location = 'works.php'
        //-->
        </script>";
 }

  


 
include("../FCKeditor/fckeditor.php");

$oFCKeditor2 = new FCKeditor('t_message' ) ;
$oFCKeditor2->BasePath = "../FCKeditor/";
$oFCKeditor2->Value = html_entity_decode($t_message);
$oFCKeditor2->Width  = '550' ;
$oFCKeditor2->Height = '400' ;
$TINY_HTML_EDITOR = $oFCKeditor2->Create(); 

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_on";
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_work.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function editWork($db,$cid)
 {
   global $PROMPT,$DOCUMENT_ROOT;
    
     if(is_uploaded_file($_FILES['Work_Photo']['tmp_name']))
	 {
			$query="select Work_Photo from jnnworks where Work_Id='$cid'";
			$db->query($query);
			$rows = $db->fetch_array();
			$m_image=$rows['Work_Photo'];

			

			if($_SERVER['SERVER_NAME']=='localhost')
			{
			  $uploadPath=$DOCUMENT_ROOT.'jnn/w_images/'.$_FILES['Work_Photo']['name'];
			  $uploadthumb_Path=$DOCUMENT_ROOT.'jnn/w_images/thumbs/';

			  @unlink($DOCUMENT_ROOT.'jnn/w_images/thumbs/'.$m_image);
			  @unlink($DOCUMENT_ROOT.'jnn/w_images/'.$m_image);
			}
			else
			{
				$uploadPath=$DOCUMENT_ROOT.'/w_images/'.$_FILES['Work_Photo']['name'];
			    $uploadthumb_Path=$DOCUMENT_ROOT.'/w_images/thumbs/';

				@unlink($DOCUMENT_ROOT.'/w_images/thumbs/'.$m_image);
				@unlink($DOCUMENT_ROOT.'/w_images/'.$m_image);
			}
			  
			

			  if(move_uploaded_file ($_FILES['Work_Photo']['tmp_name'],$uploadPath))
			   {
					chmod("$uploadPath",0777);
			   }
			   else
			   { 
				   echo "Failed to upload file Contact Site admin to fix the problem";
				   exit;
			   }

			   $target_path=$uploadPath;
			 $image = $target_path;
			 $newImageName=$_FILES['Work_Photo']['name'];
			 $thumb = new SimpleImage();
			 $thumb->load($image);
			 $width = 287;
			 //$height = 177;
			 //$thumb->resize($width,$height);
			 $thumb->resizeToWidth($width);
			 $thumb->save($uploadthumb_Path . $newImageName); 

				$c_description=addslashes($_POST["t_message"]);
			    $status=$_POST['status'];
			    $Work_Name=$_POST['Work_Name'];
			    $Work_Photo=$_FILES['Work_Photo']['name'];
				$AddedDate=date("m/d/Y");
				
				$query="update jnnworks set Status='$status',Work_Name ='$Work_Name',Work_Photo='$Work_Photo',Work_Desc='$c_description',AddedDate='$AddedDate' where Work_Id='$cid'";
				$db->query($query);
      
	 }
	 else
	 {
		        $c_description=addslashes($_POST["t_message"]);
			    $status=$_POST['status'];
			    $Work_Name=$_POST['Work_Name'];
			   	$AddedDate=date("m/d/Y");
				
				 $query="update jnnworks set Status='$status',Work_Name ='$Work_Name',Work_Desc='$c_description',AddedDate='$AddedDate' where Work_Id='$cid'";
				 $db->query($query);
	 }
      return true;
 
 
 }
 
function  GetWorkInfo($db,$cid)
 {
   global $Work_Name,$status,$status1,$t_message,$Work_Id;
   
   $query="select * from jnnworks where Work_Id='$cid'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $Work_Id=$rows['Work_Id'];
   $status=$rows['Status'];
   if($status==1)
	{ $status="selected";}
   else
	{
	   $status1="selected";
	 }
	 $t_message=stripslashes($rows['Work_Desc']);
	 $Work_Name=stripslashes($rows['Work_Name']);
	}

?>

