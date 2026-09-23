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

			

			if($_SERVER['SERVER_NAME']=='localhost')
			{
			  $uploadPath=$DOCUMENT_ROOT.'jnn/c_images/'.$_FILES['Comm_Photo']['name'];
			  $uploadthumb_Path=$DOCUMENT_ROOT.'jnn/c_images/thumbs/';

			  @unlink($DOCUMENT_ROOT.'jnn/c_images/thumbs/'.$m_image);
			  @unlink($DOCUMENT_ROOT.'jnn/c_images/'.$m_image);
			}
			else
			{
				$uploadPath=$DOCUMENT_ROOT.'/c_images/'.$_FILES['Comm_Photo']['name'];
			    $uploadthumb_Path=$DOCUMENT_ROOT.'/c_images/thumbs/';

				@unlink($DOCUMENT_ROOT.'/c_images/thumb/'.$m_image);
				@unlink($DOCUMENT_ROOT.'/c_images/'.$m_image);
			}
			  
			

			  if(move_uploaded_file ($_FILES['Comm_Photo']['tmp_name'],$uploadPath))
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
			 $newImageName=$_FILES['Comm_Photo']['name'];
			 $thumb = new SimpleImage();
			 $thumb->load($image);
			 $width = 302;
			 $height = 177;
			 $thumb->resize($width,$height);
			 $thumb->save($uploadthumb_Path . $newImageName); 

				$c_description=addslashes($_POST["t_message"]);
			    $status=$_POST['status'];
			    $Comm_Name=$_POST['Comm_Name'];
			    $Comm_Photo=$_FILES['Comm_Photo']['name'];
				$AddedDate=date("m/d/Y");
				
				$query="update municipal_comm set Status='$status',Comm_Name ='$Comm_Name',Comm_Photo='$Comm_Photo',Comm_Desc='$c_description',AddedDate='$AddedDate' where Comm_Id='$cid'";
				$db->query($query);
      
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
   global $Comm_Name,$status,$status1,$t_message,$Comm_Id;
   
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
	}

?>

