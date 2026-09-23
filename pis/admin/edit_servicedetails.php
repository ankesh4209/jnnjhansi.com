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

$emp_code=$_GET['emp_code'];

GetEmployeeDetails($db,$emp_code);
//GetDepartment($db);
//GetPosts($db);
//GetSamvarg($db);

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

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_servicedetails.html");
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
 

function GetDepartment($db,$Department='')
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
		 if($Department==$did){
		    $list_dept.="<option value='$did' selected>$d_name</option>";
		 }else{
            $list_dept.="<option value='$did'>$d_name</option>";
		 }
       }
    }
 }


function GetPosts($db,$JoinningPost=''){
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
		 if($JoinningPost==$PostId){
		    $catposts.="<option value='$PostId' style='font-family: kruti_dev_010regular;font-size:20px;' selected>$Post</option>";
		 }else{
			$catposts.="<option value='$PostId' style='font-family: kruti_dev_010regular;font-size:20px;'>$Post</option>";
		 }
       }
      // $catposts.="</select>";
     } 
}


function GetSamvarg($db,$samvarg_id=''){
	global $samvarg_list;
	$sql="select * from samvarg order by SamvargId ASC";
    $row=$db->query($sql);
    // $samvarg_list='';
	 if($db->num_rows())
     {
		 
       while($res=$db->fetch_array($row))
       {
        
         $SamvargId = $res['SamvargId'];
         $SamvargName = $res['SamvargName'];
         if($samvarg_id==$SamvargId){
		    $samvarg_list.="<option value='$SamvargId' style='font-family: kruti_dev_010regular;font-size:20px;' selected>$SamvargName</option>";
		 }else{
		    $samvarg_list.="<option value='$SamvargId' style='font-family: kruti_dev_010regular;font-size:20px;'>$SamvargName</option>";
		 }
       }
      
     } 
}

function GetGradePay($db,$gpid=''){
	global $grade_pay;
	$sql="select * from gradepay order by Gp_Id ASC";
    $row=$db->query($sql);
     if($db->num_rows())
     {
       while($res=$db->fetch_array($row))
       {
        
         $Gp_Id = $res['Gp_Id'];
         $GradePay = $res['GradePay'];
         if($Gp_Id==$gpid){
		    $grade_pay.="<option value='$Gp_Id'  selected>$GradePay</option>";
		 }else{
		    $grade_pay.="<option value='$Gp_Id' >$GradePay</option>";
		 }
       }
      
     } 
}

function  GetEmployeeDetails($db,$emp_code)
 {
   global $EmployeeCode,$JobType,$ReservedCategory,$JoinningDate,$JoinningOfficer,$JoinningOrderNo,$JoinningOrderDate,$JoinningPost,$vinimitiDate,$Department,$PermanentPost,$PermanentDate,$samvarg,$EmployeeNature,$nidesalaya,$SeniorityNo,$shreni,$ForeignService,$Salary,$TechnicalCat,$BankName,$CurrentBasicPay,$PayIncreamentMonth,$BankACNo,$GPF,$PAN,$DateOfRetirement,$GPFStartDate,$PensionStartDate,$PensionACNo,$ServiceStartDate,$shreni1,$shreni2,$shreni3,$shreni4,$FS_Y,$FS_N,$sal_5,$sal_9,$sal_15,$UBI,$PNB;
   global $JS_N,$JS_Ni,$JS_P,$JS_S,$JS_D,$IJ_Y,$IJ_N,$Department_selected,$JT_CS,$JT_MUS,$JT_NK,$JT_NCS,$JT_P,$JT_NP,$RC_M,$RC_PS,$RC_SSP,$RC_H,$RC_S,$DOCUMENT_ROOT;
   
   $query="select * from employeeinfo where EmployeeCode='$emp_code'";
   $db->query($query);
   $rows = $db->fetch_array();
   
   $EmployeeCode=stripslashes($rows['EmployeeCode']);
   $JobType=stripslashes($rows['JobType']);
   $ReservedCategory=stripslashes($rows['ReservedCategory']);
   $JoinningDate=stripslashes($rows['JoinningDate']);
   $JoinningOrderNo=stripslashes($rows['JoinningOrderNo']);
   $JoinningPost=stripslashes($rows['JoinningPost']);
   $vinimitiDate=stripslashes($rows['vinimitiDate']);
   $Department=stripslashes($rows['Department']);
   $PermanentDate=stripslashes($rows['PermanentDate']);
   $samvarg_id=stripslashes($rows['samvarg']);
   $JoinningOfficer=stripslashes($rows['JoinningOfficer']);
   $JoinningOrderDate=stripslashes($rows['JoinningOrderDate']);
   $PermanentPost=stripslashes($rows['PermanentPost']);
   $EmployeeNature=stripslashes($rows['EmployeeNature']);
   $nidesalaya=stripslashes($rows['nidesalaya']);
   $SeniorityNo=stripslashes($rows['SeniorityNo']);
   $shreni=stripslashes($rows['shreni']);
   $Salary=stripslashes($rows['Salary']);
   $TechnicalCat=stripslashes($rows['TechnicalCat']);
   $TechnicalCat=stripslashes($rows['TechnicalCat']);
   $CurrentBasicPay=stripslashes($rows['CurrentBasicPay']);
   $PayIncreamentMonth=stripslashes($rows['PayIncreamentMonth']);
   $BankName=stripslashes($rows['BankName']);
   $BankACNo=stripslashes($rows['BankACNo']);
   $GPF=stripslashes($rows['GPF']);
   $PAN=stripslashes($rows['PAN']);
   $DateOfRetirement=stripslashes($rows['DateOfRetirement']);
   $GPFStartDate=stripslashes($rows['GPFStartDate']);
   $PensionStartDate=stripslashes($rows['PensionStartDate']);
   $PensionACNo=stripslashes($rows['PensionACNo']);
   $ServiceStartDate=stripslashes($rows['ServiceStartDate']);
   $ForeignService=stripslashes($rows['ForeignService']);
   $E_Salary=stripslashes($rows['E_Salary']);
   $GradePay=stripslashes($rows['GradePay']);
   //GetDepartment($db);

   $Dob=FormatDate($Dob);
   $JoinningDate=FormatDate($JoinningDate);
   $JoinningOrderDate=FormatDate($JoinningOrderDate);
   $vinimitiDate=FormatDate($vinimitiDate);
   $PermanentDate=FormatDate($PermanentDate);
   $DateOfRetirement=FormatDate($DateOfRetirement);
   $GPFStartDate=FormatDate($GPFStartDate);
   $PensionStartDate=FormatDate($PensionStartDate);
   $ServiceStartDate=FormatDate($ServiceStartDate);
   
   GetDepartment($db,$Department);
   GetSamvarg($db,$samvarg_id);
   GetPosts($db,$JoinningPost);
   GetGradePay($db,$GradePay);

   if($JobType=="dsUnzhf;r lsok"){
       $JT_CS='selected';
   }elseif($JobType=='e.My milaoxZ'){
       $JT_MUS='selected';
   }elseif($JobType=="funs'kky; deZpkjh"){
       $JT_NK='selected';
   }elseif($JobType=='vdsUnzhf;r lsok'){
       $JT_NCS='selected';
   }elseif($JobType=='LFkkbZ'){
       $JT_P='selected';
   }elseif($JobType=='vLFkkbZ'){
       $JT_NP='selected';
   }else{
   }

   if($ReservedCategory=='Efgyk'){
       $RC_M='selected';
   }elseif($ReservedCategory=='HkwriwoZ lSfud'){
       $RC_PS='selected';
   }elseif($ReservedCategory=='LorU=rk laxzke lsukuh ds vkfJr'){
       $RC_SSP='selected';
   }elseif($ReservedCategory=='fodykax'){
       $RC_H='selected';
   }elseif($ReservedCategory=='LiksZVl dksVk'){
       $RC_S='selected';
   }else{
   }

   if($shreni=='1'){
       $shreni1='selected';
   }elseif($shreni=='2'){
       $shreni2='selected';
   }elseif($shreni=='3'){
       $shreni3='selected';
   }elseif($shreni=='4'){
       $shreni4='selected';
   }else{
   }

	if($ForeignService=='gk'){
		$FS_Y='checked';
	}else{
		$FS_N='checked';
	}
	
	if($E_Salary=='5200-20200'){
		$sal_5='selected';
	}elseif($E_Salary=='9300-34800'){
		$sal_9='selected';
	}elseif($E_Salary=='15600-39100'){
	    $sal_15='selected';
	}else{
	}

	if($BankName==";wfu;u cSad vkWQ bf.M;k"){
		$UBI='selected';
	}elseif($BankName=="iatkc us'kuy cSad"){
	    $PNB='selected';
	}else{
	}

}


?>