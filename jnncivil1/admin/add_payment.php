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



if($_POST['submit']!='')
 {
       $result=addPayment($db);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'payments.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="amount already exist.";
		  
	 }
 }

GetWorkOrders($db);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_payment.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addPayment($db)
 {
		  extract($_POST);
		  $WorkOrderNo=$WorkOrderNo;
		  $Installment=$Installment;
		  $PayDate=$_POST['PayDate'];
		  $PayDate = explode('/',$PayDate);
	      $PayDate=$PayDate[2]."-".$PayDate[1]."-".$PayDate[0]; 
		  $CheckNo=$CheckNo;
		  $CheckDate=$_POST['CheckDate'];
		  $CheckDate = explode('/',$CheckDate);
	      $CheckDate=$CheckDate[2]."-".$CheckDate[1]."-".$CheckDate[0]; 
		  $Status=$Status;
		  $Comments=$Comments;
		     
		  $insert="insert into cw_payments(WorkOrderNo,Installment,PayDate,Amount,CheckNo,CheckDate,Comments) values('$WorkOrderNo','$Installment','".$PayDate."','$Amount','$CheckNo','$CheckDate','$Comments')";
		  $db->query($insert);

		  $update1="update cw_progress set 
		  Status='$Status'
		  where WorkOrderNo='".$WorkOrderNo."'";
		  $db->query($update1);
		  
		 return true;
	 
 }

function GetWorkOrders($db)
 {
	global $WorkOrders;
	$sql="select * from cw_progress order by ProgId";
    $row=$db->query($sql);
      $WorkOrders.="<select  name='WorkOrderNo' >";
      $WorkOrders.="<option value=''>Select Work Order No</option>";
	  while($res=$db->fetch_array($row))
       {
         $WorkOrderNo = $res['WorkOrderNo'];
         
			$WorkOrders.="<option value='$WorkOrderNo' >$WorkOrderNo</option>";
		 
       }
      $WorkOrders.="</select>";
    
 }
?>
