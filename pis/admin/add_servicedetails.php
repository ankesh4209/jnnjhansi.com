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

GetDepartment($db);
GetPosts($db);
GetSamvarg($db);

if($_POST["submit"]!='')
 {
   if(addcontent($db)){
     
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'servicedetails.php'
        //-->
        </script>"; 
                   
   }  
                   
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_servicedetails.html");
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
   
   $Dob=FormatDate($Dob,'usa');
   $JoinningDate=FormatDate($JoinningDate,'usa');
   $JoinningOrderDate=FormatDate($JoinningOrderDate,'usa');
   $vinimitiDate=FormatDate($vinimitiDate,'usa');
   $PermanentDate=FormatDate($PermanentDate,'usa');
   $DateOfRetirement=FormatDate($DateOfRetirement,'usa');
   $GPFStartDate=FormatDate($GPFStartDate,'usa');
   $PensionStartDate=FormatDate($PensionStartDate,'usa');
   $ServiceStartDate=FormatDate($ServiceStartDate,'usa');
//echo '<pre>';
//print_r($_POST);
  // die;
   $sql="select * from employeeinfo where EmployeeCode='$EmployeeCode'";
   $db->query($sql);
   if ($db->num_rows())
   {	    
		   $query="update employeeinfo set 
		   JobType='".addslashes($JobType)."',
		   ReservedCategory='".addslashes($ReservedCategory)."',
		   JoinningDate='".addslashes($JoinningDate)."',
		   ServiceStartDate='".addslashes($ServiceStartDate)."',
		   JoinningOfficer='".addslashes($JoinningOfficer)."',
		   JoinningOrderNo='".addslashes($JoinningOrderNo)."',
		   SeniorityNo='".addslashes($SeniorityNo)."',
		   vinimitiDate='".addslashes($vinimitiDate)."',
		   Department='".addslashes($Department)."',
		   PermanentPost='".addslashes($PermanentPost)."',
		   PermanentDate='".addslashes($PermanentDate)."',
		   samvarg='".addslashes($samvarg)."',
		   EmployeeNature='".addslashes($EmployeeNature)."',
		   nidesalaya='".addslashes($nidesalaya)."',
		   shreni='".addslashes($shreni)."',
		   JoinningPost='".$JoinningPost."',
		   ForeignService='".addslashes($ForeignService)."',
		   TechnicalCat='".addslashes($TechnicalCat)."',
		   E_Salary='".addslashes($E_Salary)."',
		   GradePay='".addslashes($GradePay)."',
		   CurrentBasicPay='".addslashes($CurrentBasicPay)."',
		   PayIncreamentMonth='".addslashes($PayIncreamentMonth)."',
		   GPF='".addslashes($GPF)."',
		   BankName='".addslashes($BankName)."',
		   BankACNo='".addslashes($BankACNo)."',
		   PAN='".addslashes($PAN)."',
		   GPFStartDate='".addslashes($GPFStartDate)."',
   	       DateOfRetirement='".addslashes($DateOfRetirement)."',
		   PensionACNo='".addslashes($PensionACNo)."',
		   PensionStartDate='".addslashes($PensionStartDate)."' where EmployeeCode='$EmployeeCode'";
		   
		   //echo $query;die;
		   $db->query($query);
		   return 1;
	}
	else
    {   
		  $PROMPT='Employee Code does not exists.';
		
		/* if($shreni!='')
               $shreni='Js.kh&'.$shreni;
			 $query="insert into employeeinfo(EmployeeCode,JobType,ReservedCategory,JoinningDate,JoinningOfficer,JoinningOrderNo,SeniorityNo,vinimitiDate,Department,PermanentPost,PermanentDate,samvarg,EmployeeNature,nidesalaya,shreni,JoinningPost,ForeignService,TechnicalCat,E_Salary,GradePay,CurrentBasicPay,PayIncreamentMonth,GPF,BankName,BankACNo,PAN,GPFStartDate,DateOfRetirement,PensionACNo,PensionStartDate) values('".addslashes($EmployeeCode)."','".addslashes($JobType)."','".addslashes($ReservedCategory)."','".$JoinningDate."','".addslashes($JoinningOfficer)."','".$JoinningOrderNo."','".$SeniorityNo."','".$vinimitiDate."','".addslashes($Department)."','".addslashes($PermanentPost)."','".$PermanentDate."','".addslashes($samvarg)."','".addslashes($EmployeeNature)."','".addslashes($nidesalaya)."','".addslashes($shreni)."','".addslashes($JoinningPost)."','".addslashes($ForeignService)."','".addslashes($TechnicalCat)."','".$E_Salary."','".$GradePay."','".$CurrentBasicPay."','".addslashes($PayIncreamentMonth)."','".addslashes($GPF)."','".addslashes($BankName)."','".$BankACNo."','".$PAN."','".$GPFStartDate."','".$DateOfRetirement."','".addslashes($PensionACNo)."','".$PensionStartDate."')";
			$db->query($query);
			$PROMPT = "Employee service details have been added Successfully";
			$P_ID = $db->insert_id();

			return 1;*/
	}
 
 }
 

function GetDepartment($db)
 {	
	global $list_dept;
  
    $sql="select * from department order by DeptName asc";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $did= $res['DeptId'];
         $d_name = $res['DeptName'];
         $list_dept.="<option value='$did'>$d_name</option>";
       }
    }
 }


function GetPosts($db){
	global $catposts;
	$sql="select * from posts order by PostId ASC";
    $row=$db->query($sql);
     if($db->num_rows())
     {
      //$catposts.="<select  name='JoinningPost'  style='font-family: kruti_dev_010regular;font-size:20px;'>";
      //$catposts.="<option value=''>Select Post</option>";
	   while($res=$db->fetch_array($row))
       {
         $PostId = $res['PostId'];
         $Post = $res['Post'];
         $catposts.="<option value='$PostId' style='font-family: kruti_dev_010regular;font-size:20px;'>$Post</option>";
       }
      // $catposts.="</select>";
     } 
}


function GetSamvarg($db){
	global $samvarg;
	$sql="select * from samvarg order by SamvargId ASC";
    $row=$db->query($sql);
     if($db->num_rows())
     {
       while($res=$db->fetch_array($row))
       {
         $SamvargId = $res['SamvargId'];
         $SamvargName = $res['SamvargName'];
         $samvarg.="<option value='$SamvargId' style='font-family: kruti_dev_010regular;font-size:20px;'>$SamvargName</option>";
       }
      
     } 
}


?>