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

 $pdfId=$_GET['pdfId'];
getpdfinfo($db,$pdfId);


if($_POST["submit"]!='')
 {
	  $pdfId=$_POST['pdfId'];
   if(editpdffile($db,$pdfId))
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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_notice.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function editpdffile($db,$pdfId)
{
   global $PROMPT,$DOCUMENT_ROOT;

   $p_description=addslashes($_POST["file_desc"]);
   $status=$_POST['status'];
   $filename=$_FILES['pdf_file']['name'];
   $tdate=explode('/',$_POST['tdate']);
   $tdate=$tdate['2']."-".$tdate['1']."-".$tdate['0']; 
  

      if($_SERVER['SERVER_NAME']=='localhost')
	  {
		$uploadPath=$DOCUMENT_ROOT.'jnn/docs/'.$_FILES['pdf_file']['name'];
		$del_path=$DOCUMENT_ROOT.'jnn/docs/';
	  }
	  else
	  {
		$uploadPath=$DOCUMENT_ROOT.'/docs/'.$_FILES['pdf_file']['name'];
		$del_path=$DOCUMENT_ROOT.'/docs/';
	  }

	  if(is_uploaded_file($_FILES['pdf_file']['tmp_name'])){
			$query="select Pdf_Name from notice where Pdf_Id='$pdfId'";
			$db->query($query);
			$rows = $db->fetch_array();
			$filename=$rows['Pdf_Name'];

			@unlink($del_path.$filename);
	  }

	  if(move_uploaded_file ($_FILES['pdf_file']['tmp_name'],$uploadPath))
	   {
		  	

			chmod("$uploadPath",0777);
			$pdfname=$_FILES['pdf_file']['name'];
			
		     $query="update notice set Pdf_Name='$pdfname',Pdf_Desc ='$p_description',AddedDate='$tdate',status='$status' where Pdf_Id='$pdfId'";

			$db->query($query);
		    $PROMPT = "Notice has been edited successfully.";
		   
			return true;
			
	   }
	   else
	   { 
		   $PROMPT ="Failed to upload file Contact Site admin to fix the problem";
		   return false;
	   }
}


function getpdfinfo($db,$pdfId)
{
	global $p_description,$status,$tdate,$filename,$addeddate,$status1,$pdf_id;
	
	 $query="select * from notice where Pdf_Id='$pdfId'";
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

