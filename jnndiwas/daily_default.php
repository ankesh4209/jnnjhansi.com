<?php

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

error_reporting(0);

$today_date=mktime(0,0,0,date('m'),date('d'),date('Y'));

$sql="select * from complainant where tdate < '$today_date' and dft_message='0' and status='0'";
$res=$db->query($sql);
while($rows=$db->fetch_array($res))
 {
   $cid=$rows['c_id'];
   $c_date=$rows['c_date'];
   $c_date=date('d-m-Y',$c_date);
   $category=$rows['c_category'];
   $c_phone=$rows['c_contno'];
   $t_date=$rows['tdate'];
   $t_date=date('d-m-Y',$t_date);
   
   $con_officer=$rows['con_officer'];
   
    $sql1="select * from con_officer where c_id='$con_officer'";
    $res1=$db1->query($sql1);
    $rows1=$db1->fetch_array($res1);
    $con_phone=$rows1['officer_contno']; 
    
    $message1="Complaint%20No.$cid/Dt.$c_date/Cat-$category/Mo-$c_phone/Target%20Dt.-$t_date%20is%20still%20pending%20with%20you.%20For%20more%20details%20please%20contact%201800-180-5333/0522-2236803.";
    
    $url1="http://websms.one97.net/sendsms/push_sms.php?user=anoopjoshi&pwd=anoopjoshi&from=UPHDB&to=$con_phone&msg=$message1";
    $file1=fopen("$url1","r");
    fclose("$file1");
    
    $update="update complainant set dft_message='1' where c_id='$cid'";
    $db1->query($update);
 }
 
 //echo"message sent";
 
?>
