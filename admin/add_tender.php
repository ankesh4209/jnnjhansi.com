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
   global $PROMPT,$DOCUMENT_ROOT;

   $p_description=addslashes($_POST["file_desc"]);
   $status=$_POST['status'];
   $filename=$_FILES['pdf_file']['name'];
   $tdate=$_POST['tdate'];
   $tdate=explode('/',$_POST['tdate']);
   $tdate=$tdate['2']."-".$tdate['1']."-".$tdate['0']; 

      if($_SERVER['SERVER_NAME']=='localhost')
	  {
		$uploadPath=$DOCUMENT_ROOT.'jnn/docs/'.$_FILES['pdf_file']['name'];
	  }
	  else
	  {
		$uploadPath=$DOCUMENT_ROOT.'/docs/'.$_FILES['pdf_file']['name'];
	  }

	  if(move_uploaded_file ($_FILES['pdf_file']['tmp_name'],$uploadPath))
	   {
		  	chmod("$uploadPath",0777);
			$pdfname=$_FILES['pdf_file']['name'];
			$query="insert into pdffiles (Pdf_Name,Pdf_Desc,AddedDate,status) values('$pdfname','$p_description','$tdate','$status')";
		   
			$db->query($query);
		    $PROMPT = "Tender has been added successfully.";
		    $P_ID = $db->insert_id();
			return true;
			
	   }
	   else
	   { 
		   $PROMPT ="Failed to upload file Contact Site admin to fix the problem";
		   return false;
	   }
}


 

?>

