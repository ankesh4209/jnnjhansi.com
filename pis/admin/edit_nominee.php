<?php session_start();
error_reporting(1);
?>
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
include("../phplib/thumbclass.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$class1="";
$class2="current";
$class3="";

$pid=$_GET['pid'];
GetNomineeDetails($db,$pid);

if($_POST["submit"]!='')
 {
   if(addcontent($db)){
     
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'nominees.php'
        //-->
        </script>"; 
                   
   }  
                   
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_nominee.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");




ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();



function addcontent($db)
 {
   global $PROMPT,$DOCUMENT_ROOT;
   $PROMPT='';
   extract($_POST);
   
   
//echo '<pre>';
//print_r($_POST);
  // die;
   $sql="select * from employeeinfo where EmployeeCode='$EmployeeCode'";
   $db->query($sql);
   if ($db->num_rows())
   {	    $query="update nominees set 
		   EmployeeCode='".addslashes($EmployeeCode)."',
		   NomineeName='".addslashes($NomineeName)."',
		   NomineeSex='".addslashes($NomineeSex)."',
		   NomineeDob='".addslashes($NomineeDob)."',
		   NomineePF='".addslashes($NomineePF)."',
		   NomineeRelation='".addslashes($NomineeRelation)."' where NomineeId='$NomineeId'";
		   
		   //echo $query;die;
		   $db->query($query);
		   return 1;
	}
	else
    {
			  $PROMPT='Employee Code does not exists.';
	}
 
 }
 

function  GetNomineeDetails($db,$pid)
 {
   global $EmployeeCode,$NomineeName,$NomineeSex,$NomineeRelation,$NomineeDob,$NomineePF,$NomineeId;
   
   $query="select * from nominees where NomineeId='$pid'";
   $db->query($query);
   $rows = $db->fetch_array();
   $NomineeId=$pid;
   $EmployeeCode=stripslashes($rows['EmployeeCode']);
   $NomineeName=stripslashes($rows['NomineeName']);
   $NomineeSex=stripslashes($rows['NomineeSex']);
   $NomineeRelation=stripslashes($rows['NomineeRelation']);
   $NomineeDob=stripslashes($rows['NomineeDob']);
   $NomineePF=stripslashes($rows['NomineePF']);
   

}


?>