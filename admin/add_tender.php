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
         window.location = 'tender_info.php'
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

$class1="leftab_on";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";


$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_tender.html");
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
       $formattedEndDate = $tparts[0] . "-" . $tparts[1] . "-" . $tparts[2];
   } else {
       $tdate = date("Y-m-d");
       $formattedEndDate = date("d-m-Y", strtotime("+14 days"));
   }

   $start_date = !empty($_POST['start_date']) ? addslashes($_POST['start_date']) : date("d-m-Y");
   $end_date = !empty($_POST['end_date']) ? addslashes($_POST['end_date']) : $formattedEndDate;

   $docsDir = ($APP_ROOT ?? (dirname(__DIR__) . '/')) . 'docs/';
   if (!file_exists($docsDir)) {
       @mkdir($docsDir, 0777, true);
   }

   if (!empty($filename) && !empty($_FILES['pdf_file']['tmp_name'])) {
       $uploadPath = $docsDir . $filename;
       if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadPath)) {
           @chmod($uploadPath, 0755);
           $query = "insert into pdffiles (Pdf_Name,Pdf_Desc,AddedDate,StartDate,EndDate,status) values('$filename','$p_description','$tdate','$start_date','$end_date','$status')";
           $db->query($query);
           $PROMPT = "Tender has been added successfully.";
           return true;
       } else {
           $PROMPT = "Failed to upload file. Please check folder permissions.";
           return false;
       }
   } else if (!empty($p_description)) {
       $query = "insert into pdffiles (Pdf_Name,Pdf_Desc,AddedDate,StartDate,EndDate,status) values('Tender_Document','$p_description','$tdate','$start_date','$end_date','$status')";
       $db->query($query);
       $PROMPT = "Tender has been added successfully.";
       return true;
   } else {
       $PROMPT = "Please provide tender description or select a PDF file.";
       return false;
   }
}


 

?>

