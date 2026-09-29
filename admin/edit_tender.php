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

$pdfId = isset($_REQUEST['pdfId']) ? (int)$_REQUEST['pdfId'] : (isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0);
if ($pdfId > 0) {
    getpdfinfo($db, $pdfId);
}

if ($_POST["submit"] != '') {
    $pdfId = isset($_POST['pdfId']) ? (int)$_POST['pdfId'] : $pdfId;
    if (editpdffile($db, $pdfId)) {
        echo "<script type='text/javascript'>
        <!-- 
         window.location = 'tender_info.php';
        //-->
        </script>"; 
        exit;
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
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_tender.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function editpdffile($db,$pdfId)
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
       $query = "select Pdf_Name from pdffiles where Pdf_Id='$pdfId'";
       $db->query($query);
       if ($rows = $db->fetch_array()) {
           $oldFile = $rows['Pdf_Name'];
           if (!empty($oldFile) && file_exists($docsDir . $oldFile)) {
               @unlink($docsDir . $oldFile);
           }
       }

       $uploadPath = $docsDir . $filename;
       if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadPath)) {
           @chmod($uploadPath, 0755);
           $query = "update pdffiles set Pdf_Name='$filename', Pdf_Desc='$p_description', AddedDate='$tdate', StartDate='$start_date', EndDate='$end_date', status='$status' where Pdf_Id='$pdfId'";
           $db->query($query);
           $PROMPT = "Tender has been edited successfully.";
           return true;  
       } else {
           $PROMPT = "Failed to upload file. Please check folder permissions.";
           return false;
       }
   } else {
       $query = "update pdffiles set Pdf_Desc='$p_description', AddedDate='$tdate', StartDate='$start_date', EndDate='$end_date', status='$status' where Pdf_Id='$pdfId'";
       $db->query($query);
       $PROMPT = "Tender has been edited successfully.";
       return true;
   }
}


function getpdfinfo($db,$pdfId)
{
	global $p_description,$status,$tdate,$filename,$addeddate,$status1,$pdf_id;
	
	 $query="select * from pdffiles where Pdf_Id='$pdfId'";
    $db->query($query);
    $rows = $db->fetch_array();

	  $p_description=stripslashes($rows['Pdf_Desc']);
    $filename=$rows['Pdf_Name'];
    $status=$rows['status'];
    $tdate=$rows['AddedDate'];
	$tdate=explode('-',$tdate);
	$tdate=$tdate['2']."/".$tdate['1']."/".$tdate['0']; 
    $pdf_id=$rows['Pdf_Id'];
	
	
	if($status==1)
	{ $status="selected";}
   else
	{
	   $status1="selected";
	 }
	
}

?>

