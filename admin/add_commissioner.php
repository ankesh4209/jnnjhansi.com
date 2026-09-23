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

//GetCategory($db);
//GetCommunity($db);



if($_POST["submit"]!='')
 {
   if(addcommissioner($db))
	 {
     
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'commissioner_info.php'
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
$class2="leftab_off";
$class3="leftab_on";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_commissioner.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addcommissioner($db)
 {
   global $PROMPT,$DOCUMENT_ROOT;
   
   $c_description=addslashes($_POST["t_message"]);
   $status=$_POST['status'];
   $Comm_Name=$_POST['Comm_Name'];
   $Comm_Photo=$_FILES['Comm_Photo']['name'];
    $AddedDate=date("m/d/Y");
   
	  if($_SERVER['SERVER_NAME']=='localhost')
	  {
		$uploadPath=$DOCUMENT_ROOT.'jnn/c_images/'.$_FILES['Comm_Photo']['name'];
		$ThumbPath=$DOCUMENT_ROOT.'jnn/c_images/thumbs/';
	  }
	  else
	  {
		$uploadPath=$DOCUMENT_ROOT.'/c_images/'.$_FILES['Comm_Photo']['name'];
		$ThumbPath=$DOCUMENT_ROOT.'/c_images/thumbs/';
	  }

	  if(move_uploaded_file ($_FILES['Comm_Photo']['tmp_name'],$uploadPath))
	   {
		  	chmod("$uploadPath",0777);
	   }
	   else
	   { 
		   $PROMPT ="Failed to upload file Contact Site admin to fix the problem";
		   return false;
	   }


     $target_path=$uploadPath;
	 $image = $target_path;
	 $newImageName=$Comm_Photo;
	 $thumb = new SimpleImage();
	 $thumb->load($image);
	 $width = 302;
	 $height = 177;
	 $thumb->resize($width,$height);
	 $thumb->save($ThumbPath . $newImageName); 


		 $query="insert into municipal_comm (Comm_Name,Comm_Photo,Comm_Desc,Status,AddedDate) values('$Comm_Name','$Comm_Photo','$c_description','$status','$AddedDate')";
      $db->query($query);
      $PROMPT = "Commissioner Added Successfully";
      $P_ID = $db->insert_id();

  
 
 }
 
 
?>

