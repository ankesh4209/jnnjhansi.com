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
   
  
  getcommissionerinfo($db,$cid);


if($_POST["submit"]!='')
 {
   $cid=$_REQUEST['cid'];
   editCommissioner($db,$cid);
     echo "<script type='text/javascript'>
        <!-- 
         window.location = 'commissioner_info.php'
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
$class3="leftab_on";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_commissioner.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function editCommissioner($db,$cid)
 {
   global $PROMPT,$DOCUMENT_ROOT;
    
     if(is_uploaded_file($_FILES['Comm_Photo']['tmp_name']))
	 {
			$query="select Comm_Photo from municipal_comm where Comm_Id='$cid'";
			$db->query($query);
			$rows = $db->fetch_array();
			$m_image=$rows['Comm_Photo'];

			$baseDir = dirname(__DIR__);
			$uploadDir = $baseDir . '/c_images/';
			$uploadthumb_Path = $baseDir . '/c_images/thumbs/';

			if (!empty($m_image)) {
				@unlink($uploadthumb_Path . $m_image);
				@unlink($uploadDir . $m_image);
			}

			$newImageName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $_FILES['Comm_Photo']['name']);
			$uploadPath = $uploadDir . $newImageName;

			if(move_uploaded_file($_FILES['Comm_Photo']['tmp_name'], $uploadPath))
			{
				@chmod("$uploadPath", 0777);
				$thumb = new SimpleImage();
				$thumb->load($uploadPath);
				$thumb->resize(302, 177);
				$thumb->save($uploadthumb_Path . $newImageName); 

				$c_description=addslashes($_POST["t_message"]);
				$status=$_POST['status'];
				$Comm_Name=$_POST['Comm_Name'];
				$Comm_Photo=$newImageName;
				$AddedDate=date("m/d/Y");
				
				$query="update municipal_comm set Status='$status',Comm_Name ='$Comm_Name',Comm_Photo='$Comm_Photo',Comm_Desc='$c_description',AddedDate='$AddedDate' where Comm_Id='$cid'";
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
			    $Comm_Name=$_POST['Comm_Name'];
			   	$AddedDate=date("m/d/Y");
				
				 $query="update municipal_comm set Status='$status',Comm_Name ='$Comm_Name',Comm_Desc='$c_description',AddedDate='$AddedDate' where Comm_Id='$cid'";
				 $db->query($query);
	 }
      return true;
 }
 
function  getcommissionerinfo($db,$cid)
 {
   global $Comm_Name,$status,$status1,$t_message,$Comm_Id,$CURRENT_PHOTO_PREVIEW;
   
   $query="select * from municipal_comm where Comm_Id='$cid'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $Comm_Id=$rows['Comm_Id'];
   $status=$rows['Status'];
   if($status==1)
	{ $status="selected";}
   else
	{
	   $status1="selected";
	 }
	 $t_message=stripslashes($rows['Comm_Desc']);
	 $Comm_Name=stripslashes($rows['Comm_Name']);

	 $c_photo = !empty($rows['Comm_Photo']) ? $rows['Comm_Photo'] : '';
	 if (!empty($c_photo) && file_exists(dirname(__DIR__) . '/c_images/thumbs/' . $c_photo)) {
		 $CURRENT_PHOTO_PREVIEW = "<img src='../c_images/thumbs/" . htmlspecialchars($c_photo) . "' style='max-height:90px; border-radius:6px; border:1px solid #ccc; box-shadow:0 2px 5px rgba(0,0,0,0.1);' />";
	 } elseif (!empty($c_photo) && file_exists(dirname(__DIR__) . '/c_images/' . $c_photo)) {
		 $CURRENT_PHOTO_PREVIEW = "<img src='../c_images/" . htmlspecialchars($c_photo) . "' style='max-height:90px; border-radius:6px; border:1px solid #ccc; box-shadow:0 2px 5px rgba(0,0,0,0.1);' />";
	 } else {
		 $CURRENT_PHOTO_PREVIEW = "<span style='color:#888;font-size:12px;'>No photo uploaded</span>";
	 }
}

?>

