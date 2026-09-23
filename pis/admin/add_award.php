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
         window.location = 'awards.php'
        //-->
        </script>"; 
                   
   }  
                   
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_award.html");
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
   
   		        $AwardType     = $_POST['AwardType'];
				$AwardByOfficer    = $_POST['AwardByOfficer'];
				$AwardDate      = $_POST['AwardDate'];
				$Type      = $_POST['Type'];
				$Salary      = $_POST['Salary'];
				$BasicSalary      = $_POST['BasicSalary'];
				
				for($i=0;$i<count($AwardType);$i++)
				{
					 if($AwardType[$i]!="" && $AwardByOfficer[$i]!=""  && $AwardDate[$i]!="" && $Type[$i]!="" )
					  { 
						
						$insert4="insert into awards(EmployeeCode,AwardType,AwardByOfficer,AwardDate,Type)values('".$EmployeeCode."','".$AwardType[$i]."','".$AwardByOfficer[$i]."','".$AwardDate[$i]."','".$Type[$i]."')";
						
						$db->query($insert4);
					  }
				}

				return 1;
	
 
 }
 



?>