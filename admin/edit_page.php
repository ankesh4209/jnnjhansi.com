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
   editpage($db,$pid);
     echo "<script type='text/javascript'>
        <!-- 
         window.location = 'page.php'
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
$class3="leftab_off";
$class4="leftab_on";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_page.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function   editpage($db,$pid)
 {
   global $PROMPT,$DOCUMENT_ROOT,$cid,$smallimage,$bigimage,$pdf,$delsmallimage,$delbigimage,$delpdf,$scid;
     
           $p_name=addslashes($_POST["p_name"]);
           $p_description=addslashes($_POST["t_message"]);
           $status=$_POST['status'];
           $query="update page set p_name='$p_name',p_text='$p_description',status='$status' where p_id='$pid'";
           $db->query($query);
                
      return true;
 
 
 }
 
function  getpage($db,$pid)
 {
   global $pid,$pname,$status,$status1,$t_message;
   
   $query="select * from page where p_id='$pid'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $pid=$rows['p_id'];
   $pname=stripslashes($rows['p_name']);
   $status=stripslashes($rows['status']);
   if($status==1)
	{ $status="selected";}
   else
	{$status1="selected";}
	 $t_message=stripslashes($rows['p_text']);
	}

?>
