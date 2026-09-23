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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_nominee.html");
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
   		        $EmployeeCode     = $_POST['EmployeeCode'];
   		        $NomineeName     = $_POST['NomineeName'];
				$NomineeSex    = $_POST['NomineeSex'];
				$NomineeRelation      = $_POST['NomineeRelation'];
				$NomineeDob      = $_POST['NomineeDob'];
				$NomineePF      = $_POST['NomineePF'];
				
				for($i=0;$i<count($NomineeName);$i++)
				{
					 if($NomineeName[$i]!="" && $NomineeSex[$i]!=""  && $NomineeRelation[$i]!="" && $NomineeDob[$i]!="" && $NomineePF[$i]!="")
					  { 
						/*$NomineeName=$NomineeName[$i];
						$NomineeSex=$NomineeSex[$i];
						$NomineeRelation=$NomineeRelation[$i];
						$NomineeDob=$NomineeDob[$i];
						$NomineePF=$NomineePF[$i];*/
						
						$insert="insert into nominees(EmployeeCode,NomineeName,NomineeSex,NomineeRelation,NomineeDob,NomineePF)values('".$EmployeeCode."','".$NomineeName[$i]."','".$NomineeSex[$i]."','".$NomineeRelation[$i]."','".$NomineeDob[$i]."','".$NomineePF[$i]."')";
						$db->query($insert);
					  }
				}

				return 1;
	
 
 }
 



?>