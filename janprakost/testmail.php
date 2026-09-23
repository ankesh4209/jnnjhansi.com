<?php
include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");

error_reporting(0);

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$sql="select * from complainant where c_id='6'";
$res=$db->query($sql);
$rows=$db->fetch_array($res);
$name=$rows['c_name'];
$address=$rows['c_add'];

    $headers = "MIME-Version: 1.0\r\n"; 
    $headers  .= "From: UPHDB<uphdb@uphdb.com>\r\n";
    $headers .= "Content-type: text/html; charset=utf-8";
    
$conoff_to="manoj@uphdb.com";
$conoff_subject="A new complain no. has been registered";
$conoff_message="<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
                <html>
                <head>
                <title></title>
                <LINK REL=StyleSheet HREF='http://www.uphdb.com/k11.ttf' TITLE='Contemporary'>
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                 </head>
                 <body>
                <table width='600' cellsapcing='5' cellpadding='5' border='0'>
                 <tr><td width='150'>Name:</td><td style='font-family:Kruti Dev 011;font-size:20px;'>$name</td></tr>
                 <tr><td width='150'>Address:</td><td style='font-family:Kruti Dev 011;font-size:20px;'>$address</td></tr>
                 <tr><td width='150'>For more details :</td><td><a href='http://www.uphdb.com/print.php?id=6' target='_blank'>Click Here</a></td></tr>
                </table>
              </body>
              </html>";
   @mail($conoff_to,$conoff_subject,$conoff_message,$headers);
   
   echo"mail send.";

?>
