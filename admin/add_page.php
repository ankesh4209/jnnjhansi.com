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
   addpage($db);
     
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



$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_page.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addpage($db)
 {
   global $PROMPT,$DOCUMENT_ROOT;
   
   $p_name=addslashes($_POST["name"]);
   $p_description=addslashes($_POST["t_message"]);
   $status=$_POST['status'];
   
   $sql="select name from page where p_name='$p_name'";
   $db->query($sql);
	 if ($db->num_rows())
	  {	
	    $PROMPT = "Already Exist";
			$PROMPT_CLASS = "error";
			return false;
	  }
	 else
	  {
    $query="insert into page (p_name,p_text,status) values('$p_name','$p_description','$status')";
      $db->query($query);
      //$PROMPT = "Product Add Successfully";
      $P_ID = $db->insert_id();

   return true;
    }
 
 }
 

?>
