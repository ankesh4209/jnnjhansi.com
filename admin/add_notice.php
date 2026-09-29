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


$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());





if($_POST["submit"]!='')
 {
   if(uploadpdffile($db))
	 {
    
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'notice_info.php'
        //-->
        </script>"; 
	 }             
        
                   
 }

/*include("../FCKeditor/fckeditor.php");


$oFCKeditor2 = new FCKeditor('t_message' ) ;
$oFCKeditor2->BasePath = "../FCKeditor/";
$oFCKeditor2->Value = html_entity_decode($t_message);
$oFCKeditor2->Width  = '550' ;
$oFCKeditor2->Height = '400' ;
$TINY_HTML_EDITOR = $oFCKeditor2->Create(); */

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_on";
$class9="leftab_off";




$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_notice.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function uploadpdffile($db)
{
   global $PROMPT, $APP_ROOT;

   $p_description = addslashes($_POST["file_desc"] ?? '');
   $status = isset($_POST['status']) ? $_POST['status'] : '1';
   $filename = !empty($_FILES['pdf_file']['name']) ? basename($_FILES['pdf_file']['name']) : '';
   
   $tparts = !empty($_POST['tdate']) ? explode('/', $_POST['tdate']) : [];
   if (count($tparts) == 3) {
       $tdate = $tparts[2] . "-" . $tparts[1] . "-" . $tparts[0];
   } else {
       $tdate = date("Y-m-d");
   }

   $docsDir = ($APP_ROOT ?? (dirname(__DIR__) . '/')) . 'docs/';
   if (!file_exists($docsDir)) {
       @mkdir($docsDir, 0777, true);
   }

   if (!empty($filename) && !empty($_FILES['pdf_file']['tmp_name'])) {
       $uploadPath = $docsDir . $filename;
       if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadPath)) {
           @chmod($uploadPath, 0755);
           $query = "insert into notice (Pdf_Name,Pdf_Desc,AddedDate,status) values('$filename','$p_description','$tdate','$status')";
           $db->query($query);
           $PROMPT = "Notice has been added successfully.";
           return true;
       } else {
           $PROMPT = "Failed to upload file. Please check folder permissions.";
           return false;
       }
   } else if (!empty($p_description)) {
       $query = "insert into notice (Pdf_Name,Pdf_Desc,AddedDate,status) values('Notice','$p_description','$tdate','$status')";
       $db->query($query);
       $PROMPT = "Notice has been added successfully.";
       return true;
   } else {
       $PROMPT = "Please provide notice description or select a PDF file.";
       return false;
   }
}


 

?>

