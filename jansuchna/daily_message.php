<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

  $query_total="select count(a_id) as total from tbl_automation";
  $db->query($query_total);
	$row = $db->fetch_assoc();
  $TOTAL_RECORDSET = $row['total'];
  
  $query_total_pending="select count(a_id) as total from tbl_automation where app_status=1";
  $db1->query($query_total_pending);
	$row_pending = $db1->fetch_assoc();
  $TOTAL_RECORDSET_PENDING = $row_pending['total'];
  
  $query_total_complete="select count(a_id) as total from tbl_automation where app_status=2";
  $db2->query($query_total_complete);
	$row_complete = $db2->fetch_assoc();
  $TOTAL_RECORDSET_COMPLETE = $row_complete['total'];
  
  
  
  $u_message="Total%20Referance:$TOTAL_RECORDSET/Total%20Pending%20Referance:$TOTAL_RECORDSET_PENDING/Total%20Resolve%20Referance:$TOTAL_RECORDSET_COMPLETE";
  
  $url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=9935241173&msgtxt=$u_message";
  $file1=fopen("$url1","r");
  fclose("$file1");
  
  $url2="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=9411861236&msgtxt=$u_message";
  $file2=fopen("$url2","r");
  fclose("$file2");

?>
