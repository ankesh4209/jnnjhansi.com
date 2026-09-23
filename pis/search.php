<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!="")
{
 $EmployeeCode=$_POST['EmployeeCode'];
 
 SearchEmployeeInfo($db,$EmployeeCode);

 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/search.html");
}
else
{
 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/search.html");
 //SearchEmployeeInfo($db,$EmployeeCode='');
}

//ViewCorpratorType($db,$CorporatorType);

$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

 
function SearchEmployeeInfo($db,$EmployeeCode)
 {
   global $IcardImg;
   
     
   $sql="select Icard from employeeinfo where EmployeeCode='".$EmployeeCode."'";
   $res=$db->query($sql);
   	if($db->num_rows()>0)
		{
		$rows = $db->fetch_array($res);
			
		 $Icard=$rows['Icard'];

		 if($Icard!=''){
				if($_SERVER['SERVER_NAME']=='localhost' )
				{
					$IcardImg="<img src='/pis/icard/".$Icard."' width='600' height='250'>";
				}
				else
				{
					$IcardImg="<img src='/pis/icard/".$Icard."'  width='600' height='250'>";
				}
	   
		}else{
			$IcardImg="No Record Found.";
		}
		
	}
   else
   {
     $IcardImg="No Record Found.";
   }
 }

?>
