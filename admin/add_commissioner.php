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
   global $PROMPT, $APP_ROOT;
   
   $c_description = addslashes($_POST["t_message"] ?? '');
   $status = isset($_POST['status']) ? $_POST['status'] : '1';
   $Comm_Name = addslashes($_POST['Comm_Name'] ?? '');
   $Comm_Photo = !empty($_FILES['Comm_Photo']['name']) ? basename($_FILES['Comm_Photo']['name']) : '';
   $AddedDate = date("m/d/Y");
   
   $baseDir = ($APP_ROOT ?? (dirname(__DIR__) . '/')) . 'c_images/';
   $ThumbPath = $baseDir . 'thumbs/';
   if (!file_exists($baseDir)) @mkdir($baseDir, 0777, true);
   if (!file_exists($ThumbPath)) @mkdir($ThumbPath, 0777, true);

   if (!empty($Comm_Photo) && !empty($_FILES['Comm_Photo']['tmp_name'])) {
       $uploadPath = $baseDir . $Comm_Photo;
       if (move_uploaded_file($_FILES['Comm_Photo']['tmp_name'], $uploadPath)) {
           @chmod($uploadPath, 0755);
           try {
               $thumb = new SimpleImage();
               $thumb->load($uploadPath);
               $thumb->resize(302, 177);
               $thumb->save($ThumbPath . $Comm_Photo);
           } catch (Exception $e) {}
       }
   }

   $query = "insert into municipal_comm (Comm_Name,Comm_Photo,Comm_Desc,Status,AddedDate) values('$Comm_Name','$Comm_Photo','$c_description','$status','$AddedDate')";
   $db->query($query);
   $PROMPT = "Commissioner Added Successfully";
   return true;
 }
 
 
?>

