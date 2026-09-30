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
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

 $pid=$_GET['pid'];
   
  
  getpage($db,$pid);


if($_POST["submit"]!='')
 {
   $pid=$_REQUEST['pid'];
   if(editphoto($db,$pid))
	 {
     echo "<script type='text/javascript'>
        <!-- 
         window.location = 'gallary.php'
        //-->
        </script>";
	}
 }

  



$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_on";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_photo.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "CURRENT_PHOTO_PREVIEW"));
print $TEMPLATE;
flush();

function   editphoto($db,$pid)
 {
   global $PROMPT;
    
	if(is_uploaded_file($_FILES['photo']['tmp_name']))
	{
		$baseDir = dirname(__DIR__);
		$uploadDir = $baseDir . '/pic/';
		$thumbDir = $baseDir . '/pic/thumb/';

		$query="select * from tbl_gallary where g_id='$pid'";
		$db->query($query);
		$rows = $db->fetch_array();
		$old_photo=$rows['photo_name'];

		if (!empty($old_photo)) {
			@unlink($thumbDir . $old_photo);
			@unlink($thumbDir . 'thumb_' . $old_photo);
			@unlink($uploadDir . $old_photo);
		}

		$newFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $_FILES['photo']['name']);
		$uploadPath = $uploadDir . $newFileName;

		if(move_uploaded_file ($_FILES['photo']['tmp_name'], $uploadPath))
		{
			@chmod("$uploadPath", 0777);
		}
		else
		{ 
			$PROMPT = "Failed to upload file. Please contact site admin.";
			return false;
		}

		$s_thumb = getImagescales($uploadPath, 200, 150);
		$n_width = $s_thumb['width'];
		$n_height = $s_thumb['height'];
		$ThumbPath = $thumbDir . $newFileName;

		if (@$_FILES['photo']['type']=="image/gif")
		{
			$im = @imagecreatefromgif($uploadPath);
			if ($im) {
				$newimage = imagecreatetruecolor($n_width, $n_height);
				imagecopyresized($newimage, $im, 0, 0, 0, 0, $n_width, $n_height, imagesx($im), imagesy($im));
				imagegif($newimage, $ThumbPath);
				@imagedestroy($newimage);
				@imagedestroy($im);
			}
		}
		elseif (@$_FILES['photo']['type']=="image/png")
		{
			$im = @imagecreatefrompng($uploadPath);
			if ($im) {
				$newimage = imagecreatetruecolor($n_width, $n_height);
				imagealphablending($newimage, false);
				imagesavealpha($newimage, true);
				imagecopyresampled($newimage, $im, 0, 0, 0, 0, $n_width, $n_height, imagesx($im), imagesy($im));
				imagepng($newimage, $ThumbPath);
				@imagedestroy($newimage);
				@imagedestroy($im);
			}
		}
		else
		{
			$im = @imagecreatefromjpeg($uploadPath);
			if ($im) {
				$newimage = imagecreatetruecolor($n_width, $n_height);
				imagecopyresampled($newimage, $im, 0, 0, 0, 0, $n_width, $n_height, imagesx($im), imagesy($im));
				imagejpeg($newimage, $ThumbPath, 85);
				@imagedestroy($newimage);
				@imagedestroy($im);
			}
		}

		$status = $_POST['status'];
		$query = "update tbl_gallary set photo_name='$newFileName', status='$status' where g_id='$pid'";
		$db->query($query);
	}
	else
	{
		$status = $_POST['status'];
		$query = "update tbl_gallary set status='$status' where g_id='$pid'";
		$db->query($query);
	}

	return true;
 }

 function getImagescales($originalImage,$toWidth,$toHeight){
    
    // Get the original geometry and calculate scales
    list($width, $height) = getimagesize($originalImage);
    $xscale=$width/$toWidth;
    $yscale=$height/$toHeight;
    
    // Recalculate new size with default ratio
    if ($yscale>$xscale){
        $new_width = round($width * (1/$yscale));
        $new_height = round($height * (1/$yscale));
    }
    else {
        $new_width = round($width * (1/$xscale));
        $new_height = round($height * (1/$xscale));
    }
    
    $dimention['width']=$new_width;
	$dimention['height']=$new_height;
    return $dimention;
}

 
function  getpage($db,$pid)
 {
   global $pid,$pname,$status,$status1,$t_message,$CURRENT_PHOTO_PREVIEW;
   
   $query="select * from tbl_gallary where g_id='$pid'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $pid=$rows['g_id'];
   $photoname=stripslashes($rows['photo_name']);
   $status=stripslashes($rows['status']);
   if($status==1)
	{ $status="selected";}
   else
	{$status1="selected";}

   if (!empty($photoname) && file_exists(dirname(__DIR__) . '/pic/thumb/' . $photoname)) {
	   $CURRENT_PHOTO_PREVIEW = "<img src='../pic/thumb/" . htmlspecialchars($photoname) . "' style='max-height:100px; border-radius:6px; border:1px solid #ccc; box-shadow:0 2px 5px rgba(0,0,0,0.1);' />";
   } elseif (!empty($photoname) && file_exists(dirname(__DIR__) . '/pic/' . $photoname)) {
	   $CURRENT_PHOTO_PREVIEW = "<img src='../pic/" . htmlspecialchars($photoname) . "' style='max-height:100px; border-radius:6px; border:1px solid #ccc; box-shadow:0 2px 5px rgba(0,0,0,0.1);' />";
   } else {
	   $CURRENT_PHOTO_PREVIEW = "<span style='color:#888;font-size:12px;'>No photo available</span>";
   }
}

?>
