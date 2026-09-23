<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: ../login.php"); 				
	  exit;
  }
?>

<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/thumbclass.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());


if(isset($_POST['submit']) && $_POST['submit']=='Submit')
{
  $result=addcivilwork($db);
       if($result)
	  {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'civilwork_photo.php?msg=succ'
        //-->
        </script>";   
	  }
}


 


$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_civilwork_photo.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

 
function addcivilwork($db)
{
	 // extract($_POST); 
	 
	  //print_r($_FILES);
	
$PayDate=$_POST['date1']; 
$date2=$_POST['date2'];
$CheckDate = explode('/',$date2);
$CheckDate=$CheckDate[2]."-".$CheckDate[1]."-".$CheckDate[0]; 
$Status=$_POST['status'];
$WardNo=$_POST['WardNo'];
$WorkName=$_POST['WorkName']; 
$uploadPath="";
$image_under="";
if($_FILES['image_under']['name']!=""){

if($_SERVER['SERVER_NAME']=='localhost')
{
 $uploadPath=$DOCUMENT_ROOT.'jnncivil/payment/'.$_FILES['image_under']['name'];
}
else
{
	 // $uploadPath='../payment/'.rand(0,99).'_'.$_FILES['image_under']['name'];
	  $image_under=rand(0,99).'_'.$_FILES['image_under']['name'];
	   $uploadPath='../payment/'.$image_under;
}

 
if(move_uploaded_file ($_FILES['image_under']['tmp_name'],$uploadPath))
{
  //echo "Successfully uploaded the mage";
	chmod("$uploadPath",0777);
}
else
{ 
   $PROMPT= "Failed to upload file Contact Site admin1 to fix the problem";
   return false;
}
//$image_under=$_FILES['image_under']['name'];
}

$image_completed="";
$uploadPath="";
if($_FILES['image_completed']['name']!=""){
 if($_SERVER['SERVER_NAME']=='localhost')
{
 $uploadPath=$DOCUMENT_ROOT.'jnncivil/payment/'.$_FILES['image_completed']['name'];
}
else
{
	  $image_completed=rand(0,99).'_'.$_FILES['image_completed']['name'];
	    $uploadPath='../payment/'.$image_completed;
}

if(move_uploaded_file ($_FILES['image_completed']['tmp_name'],$uploadPath))
{
  //echo "Successfully uploaded the mage";
	chmod("$uploadPath",0777);
}
else
{ 
   $PROMPT= "Failed to upload file Contact Site admin1 to fix the problem";
   return false;
}
 
}
	 
	
$image_inprogress="";
$uploadPath="";
if($_FILES['image_inprogress']['name']!=""){
 if($_SERVER['SERVER_NAME']=='localhost')
{
 $uploadPath=$DOCUMENT_ROOT.'jnncivil/payment/'.$_FILES['image_inprogress']['name'];
}
else
{
	 // $uploadPath='../payment/'.rand(0,99).'_'.$_FILES['image_inprogress']['name'];
	  
	  	  $image_inprogress=rand(0,99).'_'.$_FILES['image_inprogress']['name'];
	        $uploadPath='../payment/'.$image_inprogress;
	  
	  
}

if(move_uploaded_file ($_FILES['image_inprogress']['tmp_name'],$uploadPath))
{
  //echo "Successfully uploaded the mage";
	chmod("$uploadPath",0777);
}
else
{ 
   $PROMPT= "Failed to upload file Contact Site admin1 to fix the problem";
   return false;
}
//$image_inprogress=$_FILES['image_inprogress']['name'];
}
	 	 
      $insert="insert into cw_photo(wardno,name_work,start_photo,inprogess_photo,complete_photo,amount,date2,created,status) values('$WardNo','$WorkName','$image_under','$image_inprogress','$image_completed','$PayDate','$CheckDate',NOW(),'$Status')";  
	  $db->query($insert);  
	  return true; 
}
 
 
function GetMads($db)
 {
	global $madname;
	$sql="select * from cw_mads order by MadId";
    $row=$db->query($sql);
      $madname.="<select  name='MadId'  style='font-family: kruti_dev_010regular;font-size:15px;' onchange='ShowMadInfo(this.value)'>";
      $madname.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
         $MadId = $res['MadId'];
         $MadName = $res['MadName'];
        
			$madname.="<option value='$MadId' style='font-family: kruti_dev_010regular;font-size:15px;'>$MadName</option>";
		 
       }
      $madname.="</select>";
    
 }



function GetRatelist($db)
{
	global $RateList,$qty;
	$query="select * from cw_ratelist order by RateId ASC";
    $db->query($query);
	$i=0;
	$arrRateList=array(); 
	while($rows = $db->fetch_array())
	{
	  
	  $RateList.="<tr><td>".$rows['RateId']."</td><td><input type='checkbox' name = rid[] value = '".$rows['RateId']."'></td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td>".$rows['Rate']."</td><td><input type='text' name='qty-".$rows['RateId']."'  value = '' size='5'></td></tr>";
	  $i++;

	}

}

function GetJECodes($db,$db1)
 {
	global $JEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where  shreni='3' and Department='4' order by EmployeeCode ASC";
    $row=$db->query($sql);
      $JEofficers.="<select  name='JEEmployeeCode'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $JEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $JEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $JEofficers.="</select>";
    
 }

 function GetAECodes($db,$db1)
 {
	global $AEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='2' and Department='4' order by EmployeeName ASC";
    $row=$db->query($sql);
      $AEofficers.="<select  name='AEEmployeeCode'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $AEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $AEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $AEofficers.="</select>";
    
 }

 function GetEXnCodes($db,$db1)
 {
	global $EXnofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='2' and Department='4' order by EmployeeCode ASC";
    $row=$db->query($sql);
      $EXnofficers.="<select  name='EXnEmployeeCode'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $EXnofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $EXnofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $EXnofficers.="</select>";
    
 }

 function GetCECodes($db,$db1)
 {
	global $CEofficers;
	$sql="select EmployeeCode,JoinningPost,EmployeeName from employeeinfo where shreni='1' and Department='4' order by EmployeeCode ASC";
    $row=$db->query($sql);
      $CEofficers.="<select  name='CEofficers'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $CEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            $sql1="select Post from posts where PostId=".$res['JoinningPost']."";
			$row1=$db1->query($sql1);
			$res1=$db1->fetch_array($row1);
			$PostName=$res1['Post'];

			$EmployeeCode = $res['EmployeeCode'];
			$EmployeeName = $res['EmployeeName'];
            $CEofficers.="<option value='$EmployeeCode' >$PostName $EmployeeName dksM $EmployeeCode </option>";
		 
       }
      $CEofficers.="</select>";
    
 }

 
?>
