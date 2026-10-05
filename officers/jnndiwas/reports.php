<?php



include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

error_reporting(1);

$suplist.="<table cellspacing='2' cellpadding='2' border='0' width='80%s'>";
$sql="select * from officer";
$res=$db->query($sql);
while($rows=$db->fetch_array($res))
 {
   $id=$rows['o_id'];
   $degisnation=$rows['off_desi'];
   $name=$rows['officer_name'];
   $phone=$rows['officer_contno'];
   $address=$rows['officer_add'];
   $email=$rows['email'];
   
   $suplist.="<tr><td colspan='4'>Supervisory officer</td></tr>
               <tr>
                <td width='20%'>$degisnation</td>
                <td width='15%'>$name</td>
                <td width='15%'>$phone</td>
                <td width='30%'>$address</td>
                <td width='20%'>$email</td>
               </tr>";
               
               $suplist.="<tr>
                          <td colspan='4'>
                           <table width='90%' cellspacing='3' cellpadding='3' border='0' align='right'>
                           <tr>
                           <td colspan='4'>Concerned officer</td>
                           </tr>";
    $sql1="select * from con_officer where s_id='$id'";
    $res1=$db1->query($sql1);
    while($rows1=$db1->fetch_array($res1))
     {
       $degisnation1=$rows1['off_desi'];
       $name1=$rows1['officer_name'];
       $phone1=$rows1['officer_contno'];
       $address1=$rows1['officer_add'];
       $email1=$rows1['email'];
       
       $suplist.=" <tr>
                      <td>$degisnation1</td>
                      <td>$name1</td>
                      <td>$phone1</td>
                      <td>$address1</td>
                      <td>$email1</td>
                     </tr>
                     ";
     
     }
    $suplist.= "</table>
     </td>
     </tr>";
    
 }
 $suplist.="</table>";
 echo $suplist;
?>
