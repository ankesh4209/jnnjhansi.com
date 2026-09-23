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

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function   editphoto($db,$pid)
 {
   global $PROMPT,$DOCUMENT_ROOT,$cid,$smallimage,$bigimage,$pdf,$delsmallimage,$delbigimage,$delpdf,$scid;
    
	
	 if(is_uploaded_file($_FILES['photo']['tmp_name']))
	 {
		      if($_SERVER['SERVER_NAME']=='localhost')
			  {
				$uploadPath=$DOCUMENT_ROOT.'jnnweb/pic/'.$_FILES['photo']['name'];
			  }
			  else
			  {
				$uploadPath=$DOCUMENT_ROOT.'/pic/'.$_FILES['photo']['name'];
			  }
			
		   if(move_uploaded_file ($_FILES['photo']['tmp_name'],$uploadPath))
		   {
			  //echo "Successfully uploaded the mage";die;
				chmod("$uploadPath",0777);
		   }
		   else
		   { 
			   $PROMPT= "Failed to upload file Contact Site admin to fix the problem";
			   exit;
		    }
		  
			  $query="select * from tbl_gallary where g_id='$pid'";
			  $db->query($query);
			  $rows = $db->fetch_array();
			  $photo=$rows['photo_name'];
			  if($_SERVER['SERVER_NAME']=='localhost')
				{
				  @unlink($DOCUMENT_ROOT.'jnnweb/pic/thumb/'.$photo);
				  @unlink($DOCUMENT_ROOT.'jnnweb/pic/thumb/thumb_'.$photo);
				  @unlink($DOCUMENT_ROOT.'jnnweb/pic/'.$photo);
				}
				else
				{
					
					@unlink($DOCUMENT_ROOT.'/pic/thumb/'.$photo);
					@unlink($DOCUMENT_ROOT.'/pic/thumb/thumb_'.$photo);
					@unlink($DOCUMENT_ROOT.'/pic/'.$photo);
				}

			  ///////// Start the thumbnail generation//////////////
			

			$s_thumb=getImagescales($uploadPath,150,150);
			$b_thumb=getImagescales($uploadPath,500,450);
			
			$n_width=$s_thumb['width'];
			$n_height=$s_thumb['height'];

			$t_width=$b_thumb['width'];
			$t_height=$b_thumb['height'];

			if($_SERVER['SERVER_NAME']=='localhost')
			{
				$ThumbPath=$DOCUMENT_ROOT.'jnnweb/pic/thumb/'.$_FILES['photo']['name'];   // Path where thumb nail image will be stored
				$ThumbPath1=$DOCUMENT_ROOT.'jnnweb/pic/thumb/thumb_'.$_FILES['photo']['name'];   // Path where thumb nail image will be stored
			}
			else
			{
				$ThumbPath=$DOCUMENT_ROOT.'/pic/thumb/'.$_FILES['photo']['name'];
				$ThumbPath1=$DOCUMENT_ROOT.'/pic/thumb/thumb_'.$_FILES['photo']['name'];
				
			}
			if (!($_FILES[photo][type] =="image/pjpeg" ||$_FILES[photo][type] =="image/jpeg" || $_FILES[photo][type]=="image/gif"))
{
					$PROMPT= "Your uploaded file must be of JPG or GIF. Other file types are not allowed<BR>";
					return false;
			}
			/////////////////////////////////////////////// Starting of GIF thumb nail creation///////////
			if (@$_FILES[photo][type]=="image/gif")
			{
				$im=ImageCreateFromGIF($uploadPath);
				$width=ImageSx($im);              // Original picture width is stored
				$height=ImageSy($im);                  // Original picture height is stored
				$newimage=imagecreatetruecolor($n_width,$n_height);
				$big_newimage=imagecreatetruecolor($t_width,$t_width);
				imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
				imageCopyResized($big_newimage,$im,0,0,0,0,$t_width,$t_height,$width,$height);
				if (function_exists("imagegif")) {
				Header("Content-type: image/gif");
				ImageGIF($newimage,$ThumbPath);
				ImageGIF($big_newimage,$ThumbPath1);
			}
			elseif (function_exists("imagejpeg")) {
				Header("Content-type: image/jpeg");
				ImageJPEG($newimage,$ThumbPath);
				ImageJPEG($big_newimage,$ThumbPath1);
			}
			chmod("$ThumbPath",0777);
			}////////// end of gif file thumb nail creation//////////

			////////////// starting of JPG thumb nail creation//////////
			if($_FILES[photo][type]=="image/pjpeg" || $_FILES[photo][type]=="image/jpeg")
			{
				$im=ImageCreateFromJPEG($uploadPath); 
				$width=ImageSx($im);              // Original picture width is stored
				$height=ImageSy($im);             // Original picture height is stored
				$newimage=imagecreatetruecolor($n_width,$n_height);
				$big_newimage=imagecreatetruecolor($t_width,$t_height);                
				imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
				imageCopyResized($big_newimage,$im,0,0,0,0,$t_width,$t_height,$width,$height);
				ImageJpeg($newimage,$ThumbPath);
				ImageJpeg($big_newimage,$ThumbPath1);
				chmod("$ThumbPath",0777);
			}
			////////////////  End of JPG thumb nail creation //////////

			$photo_name=$_FILES['photo']['name'];
           $status=$_POST['status'];
           $query="update tbl_gallary set photo_name='$photo_name',status='$status' where g_id='$pid'";
           $db->query($query);
	 }
	 else
	 {
           $status=$_POST['status'];
           $query="update tbl_gallary set status='$status' where g_id='$pid'";
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
   global $pid,$pname,$status,$status1,$t_message;
   
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
	 $photoname=stripslashes($rows['photo_name']);
	}

?>
