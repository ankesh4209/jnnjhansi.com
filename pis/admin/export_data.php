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


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

$db3=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db3->open() or die($db3->error());



$query="select * from employeeinfo order by EmployeeCode ASC";
    
$db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $pid=$rows['EmployeeId'];
			  $JoinningPost=$rows['JoinningPost'];
			  $Department=$rows['Department'];
			  $samvarg=$rows['samvarg'];
			  $GradePay=$rows['GradePay'];
			  $emp_name=stripslashes($rows['EmployeeName']);
              $emp_code=stripslashes($rows['EmployeeCode']);
			
              $sql_post='select * from posts where PostId='.$JoinningPost;
			  $res_post=$db1->query($sql_post);
			  $rows_post = $db1->fetch_array();
			  $PostName=$rows_post['Post'];

			  $sql_samvarg='select * from samvarg where SamvargId='.$samvarg;
			  $res_samvarg=$db1->query($sql_samvarg);
			  $rows_samvarg = $db1->fetch_array();
			  $Samvarg=$rows_samvarg['Post'];

			  $sql_department='select * from department where DeptId='.$Department;
			  $res_department=$db1->query($sql_department);
			  $rows_department = $db1->fetch_array();
			  $DeptName=$rows_department['DeptName'];

			  $sql_gradepay='select * from gradepay where Gp_Id='.$GradePay;
			  $res_gradepay=$db1->query($sql_gradepay);
			  $rows_gradepay = $db1->fetch_array();
			  $GradePay=$rows_gradepay['GradePay'];


			  
			  $status=$rows['Status'];
			  if($status==1)
			    $status="Active";
			  else
				$status="Inactive";
		$slno++;	  
 }
 
?>