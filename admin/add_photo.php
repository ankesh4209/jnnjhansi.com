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


if($_POST["submit"]!='')
 {
   if(addphoto($db))
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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_photo.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addphoto($db)
 {
   global $PROMPT, $APP_ROOT;
   
   $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
   $photo_name = !empty($_FILES['photo']['name']) ? basename($_FILES['photo']['name']) : '';
   
   if (empty($photo_name) || empty($_FILES['photo']['tmp_name'])) {
       $PROMPT = "Please select a photo to upload.";
       return false;
   }

   $baseDir = ($APP_ROOT ?? (dirname(__DIR__) . '/')) . 'pic/';
   $thumbDir = $baseDir . 'thumb/';
   if (!file_exists($baseDir)) @mkdir($baseDir, 0777, true);
   if (!file_exists($thumbDir)) @mkdir($thumbDir, 0777, true);

   $uploadPath = $baseDir . $photo_name;
   if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
       @chmod($uploadPath, 0755);
       try {
           $thumb = new SimpleImage();
           $thumb->load($uploadPath);
           $thumb->resize(150, 150);
           $thumb->save($thumbDir . $photo_name);

           $thumbBig = new SimpleImage();
           $thumbBig->load($uploadPath);
           $thumbBig->resize(500, 450);
           $thumbBig->save($thumbDir . 'thumb_' . $photo_name);
       } catch (Exception $e) {}

       $query = "insert into tbl_gallary (photo_name,status,AddedDate) values('$photo_name','$status',NOW())";
       $db->query($query);
       $PROMPT = "Photo added successfully to gallery.";
       return true;
   } else {
       $PROMPT = "Failed to upload file. Please check folder permissions.";
       return false;
   }
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


?>
