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

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "CURRENT_PHOTO_PREVIEW"));
print $TEMPLATE;
flush();

function editWork($db,$cid)
 {
   global $PROMPT;
    
     if(is_uploaded_file($_FILES['Work_Photo']['tmp_name']))
	 {
			$query="select Work_Photo from jnnworks where Work_Id='$cid'";
			$db->query($query);
			$rows = $db->fetch_array();
			$m_image=$rows['Work_Photo'];

			$baseDir = dirname(__DIR__);
			$uploadDir = $baseDir . '/w_images/';
			$uploadthumb_Path = $baseDir . '/w_images/thumbs/';

			if (!empty($m_image)) {
				@unlink($uploadthumb_Path . $m_image);
				@unlink($uploadDir . $m_image);
			}

			$newImageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $_FILES['Work_Photo']['name']);
			$uploadPath = $uploadDir . $newImageName;

			if(move_uploaded_file($_FILES['Work_Photo']['tmp_name'], $uploadPath))
			{
				@chmod("$uploadPath", 0777);
				$thumb = new SimpleImage();
				$thumb->load($uploadPath);
				$thumb->resizeToWidth(287);
				$thumb->save($uploadthumb_Path . $newImageName); 

				$c_description=addslashes($_POST["t_message"]);
				$status=$_POST['status'];
				$Work_Name=$_POST['Work_Name'];
				$Work_Photo=$newImageName;
				$AddedDate=date("m/d/Y");
				
				$query="update jnnworks set Status='$status',Work_Name ='$Work_Name',Work_Photo='$Work_Photo',Work_Desc='$c_description',AddedDate='$AddedDate' where Work_Id='$cid'";
				$db->query($query);
			}
			else
			{ 
				echo "Failed to upload file. Please contact site admin.";
				exit;
			}
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
   global $Work_Name,$status,$status1,$t_message,$Work_Id,$CURRENT_PHOTO_PREVIEW;
   
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

	 $w_photo = !empty($rows['Work_Photo']) ? $rows['Work_Photo'] : '';
	 if (!empty($w_photo) && file_exists(dirname(__DIR__) . '/w_images/thumbs/' . $w_photo)) {
		 $CURRENT_PHOTO_PREVIEW = "<img src='../w_images/thumbs/" . htmlspecialchars($w_photo) . "' style='max-height:90px; border-radius:6px; border:1px solid #ccc; box-shadow:0 2px 5px rgba(0,0,0,0.1);' />";
	 } elseif (!empty($w_photo) && file_exists(dirname(__DIR__) . '/w_images/' . $w_photo)) {
		 $CURRENT_PHOTO_PREVIEW = "<img src='../w_images/" . htmlspecialchars($w_photo) . "' style='max-height:90px; border-radius:6px; border:1px solid #ccc; box-shadow:0 2px 5px rgba(0,0,0,0.1);' />";
	 } else {
		 $CURRENT_PHOTO_PREVIEW = "<span style='color:#888;font-size:12px;'>No photo uploaded</span>";
	 }
}

?>

