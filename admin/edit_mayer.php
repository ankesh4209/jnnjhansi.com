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

 $mid=$_GET['mid'];
   
  
  getmayerinfo($db,$mid);


if($_POST["submit"]!='')
 {
   $mid=$_REQUEST['mid'];
   editMayer($db,$mid);
     echo "<script type='text/javascript'>
        <!-- 
         window.location = 'mayer_info.php'
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
$class2="leftab_on";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_mayer.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "CURRENT_PHOTO_PREVIEW"));
print $TEMPLATE;
flush();

function   editMayer($db,$mid)
 {
   global $PROMPT;
    
     if(is_uploaded_file($_FILES['Mayer_Photo']['tmp_name']))
	 {
			$query="select Mayer_Photo from mayers where Mayer_Id='$mid'";
			$db->query($query);
			$rows = $db->fetch_array();
			$m_image=$rows['Mayer_Photo'];

			$baseDir = dirname(__DIR__);
			$uploadDir = $baseDir . '/m_images/';
			$uploadthumb_Path = $baseDir . '/m_images/thumbs/';

			if (!empty($m_image)) {
				@unlink($uploadthumb_Path . $m_image);
				@unlink($uploadDir . $m_image);
			}

			$newImageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $_FILES['Mayer_Photo']['name']);
			$uploadPath = $uploadDir . $newImageName;

			if(move_uploaded_file($_FILES['Mayer_Photo']['tmp_name'], $uploadPath))
			{
				@chmod("$uploadPath", 0777);
				$thumb = new SimpleImage();
				$thumb->load($uploadPath);
				$thumb->resize(302, 177);
				$thumb->save($uploadthumb_Path . $newImageName);

				$m_description=addslashes($_POST["t_message"]);
				$status=$_POST['status'];
				$Mayer_Name=$_POST['Mayer_Name'];
				$Mayer_Photo=$newImageName;
				$AddedDate=date("m/d/Y");
				
				$query="update mayers set Status='$status',Mayer_Name ='$Mayer_Name',Mayer_Photo='$Mayer_Photo',Mayer_Desc='$m_description',AddedDate='$AddedDate' where Mayer_Id='$mid'";
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
		        $m_description=addslashes($_POST["t_message"]);
			    $status=$_POST['status'];
			    $Mayer_Name=$_POST['Mayer_Name'];
			    $AddedDate=date("m/d/Y");
				
				 $query="update mayers set Status='$status',Mayer_Name ='$Mayer_Name',Mayer_Desc='$m_description',AddedDate='$AddedDate' where Mayer_Id='$mid'";
				$db->query($query);
	 }
      return true;
 }
 
function  getmayerinfo($db,$mid)
 {
   global $nid,$Mayer_Name,$status,$status1,$t_message,$Mayer_Id,$CURRENT_PHOTO_PREVIEW;
   
   $query="select * from mayers where Mayer_Id='$mid'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $Mayer_Id=$rows['Mayer_Id'];
   $status=$rows['Status'];
   if($status==1)
	{ $status="selected";}
   else
	{
	   $status1="selected";
	 }
	 $t_message=stripslashes($rows['Mayer_Desc']);
	 $Mayer_Name=stripslashes($rows['Mayer_Name']);
	 
	 $currentPhoto = $rows['Mayer_Photo'];
	 if (!empty($currentPhoto) && file_exists("../m_images/thumbs/" . $currentPhoto)) {
		 $CURRENT_PHOTO_PREVIEW = "<div style='margin-bottom:8px;'><img src='../m_images/thumbs/{$currentPhoto}' style='width:90px; height:90px; object-fit:cover; border-radius:8px; border:2px solid #E2E8F0; display:block;'><span style='font-size:11px; color:#666;'>Current: {$currentPhoto}</span></div>";
	 } elseif (!empty($currentPhoto) && file_exists("../m_images/" . $currentPhoto)) {
		 $CURRENT_PHOTO_PREVIEW = "<div style='margin-bottom:8px;'><img src='../m_images/{$currentPhoto}' style='width:90px; height:90px; object-fit:cover; border-radius:8px; border:2px solid #E2E8F0; display:block;'><span style='font-size:11px; color:#666;'>Current: {$currentPhoto}</span></div>";
	 } else {
		 $CURRENT_PHOTO_PREVIEW = "<span style='font-size:11px; color:#999;'>No photo uploaded yet</span>";
	 }
 }

?>

