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

//GetCategory($db);
//GetCommunity($db);



if($_POST["submit"]!='')
 {
   if(addmayer($db))
	 {
     
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'mayer_info.php'
        //-->
        </script>"; 
	 }             
        
                   
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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_mayer.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addmayer($db)
 {
   global $PROMPT,$DOCUMENT_ROOT;
   
   $m_description=addslashes($_POST["t_message"]);
   $status=$_POST['status'];
   $Mayer_Name=$_POST['Mayer_Name'];
   $Mayer_Photo=$_FILES['Mayer_Photo']['name'];
    $AddedDate=date("m/d/Y");
   
	  if($_SERVER['SERVER_NAME']=='localhost')
	  {
		$uploadPath=$DOCUMENT_ROOT.'jnnweb/m_images/'.$_FILES['Mayer_Photo']['name'];
	  }
	  else
	  {
		$uploadPath=$DOCUMENT_ROOT.'/m_images/'.$_FILES['Mayer_Photo']['name'];
	  }

	  if(move_uploaded_file ($_FILES['Mayer_Photo']['tmp_name'],$uploadPath))
	   {
		  	chmod("$uploadPath",0777);
	   }
	   else
	   { 
		   $PROMPT ="Failed to upload file Contact Site admin to fix the problem";
		   return false;
	   }


///////// Start the thumbnail generation//////////////
$n_width=302;          // Fix the width of the thumb nail images
$n_height=177;         // Fix the height of the thumb nail imaage

if($_SERVER['SERVER_NAME']=='localhost')
{
	$ThumbPath=$DOCUMENT_ROOT.'jnnweb/m_images/thumbs/'.$_FILES['Mayer_Photo']['name'];   // Path where thumb nail image will be stored
}
else
{
	$ThumbPath=$DOCUMENT_ROOT.'/m_images/thumbs/'.$_FILES['Mayer_Photo']['name'];
}

if (!($_FILES['Mayer_Photo']['type'] =="image/jpeg" OR $_FILES[e_image][type]=="image/gif")){
	$PROMPT = "Your uploaded file must be of JPG or GIF. Other file types are not allowed<BR>";
return false;}
/////////////////////////////////////////////// Starting of GIF thumb nail creation///////////

if (@$_FILES['Mayer_Photo']['type']=="image/gif" OR @$_FILES['Mayer_Photo']['type']=="image/jpeg")
{
$im=ImageCreateFromGIF($uploadPath);
$width=ImageSx($im);              // Original picture width is stored
$height=ImageSy($im);                  // Original picture height is stored
$newimage=imagecreatetruecolor($n_width,$n_height);
imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
if (function_exists("imagegif")) {
Header("Content-type: image/gif");
ImageGIF($newimage,$ThumbPath);
}
elseif (function_exists("imagejpeg")) {
Header("Content-type: image/jpeg");
ImageJPEG($newimage,$ThumbPath);
}
chmod("$ThumbPath",0777);
}////////// end of gif file thumb nail creation//////////

////////////// starting of JPG thumb nail creation//////////
if($_FILES['Mayer_Photo']['type']=="image/gif" || $_FILES['Mayer_Photo']['type']=="image/jpeg"){
$im=ImageCreateFromJPEG($uploadPath); 
$width=ImageSx($im);              // Original picture width is stored
$height=ImageSy($im);             // Original picture height is stored
$newimage=imagecreatetruecolor($n_width,$n_height);                 
imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
ImageJpeg($newimage,$ThumbPath);
chmod("$ThumbPath",0777);
}
////////////////  End of JPG thumb nail creation //////////

		 $query="insert into mayers (Mayer_Name,Mayer_Photo,Mayer_Desc,Status,AddedDate) values('$Mayer_Name','$Mayer_Photo','$m_description','$status','$AddedDate')";
      $db->query($query);
      $PROMPT = "Mayer Added Successfully";
      $P_ID = $db->insert_id();

  
 
 }
 
 
?>

