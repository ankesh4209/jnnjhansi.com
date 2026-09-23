<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

  
    $cname=addslashes($_GET['cname']);
    $caddress=addslashes($_GET['address']);
    $city=addslashes($_GET['city']);
    $nature=addslashes($_GET['cnature']);
    $c_officer=$_GET['c_officer'];
    $ccontact=addslashes($_GET['contact']);
    $cofficer=addslashes($_GET['officer']);
    $cdate=mktime(0,0,0,date('m'),date('d'),date('Y'));
    $cmode=$_GET['cmode'];
    $summary=addslashes($_GET['summary']);
    $oldcomplain=$_GET['oldcno'];
    $comtime=$_GET['ctime'];
    $ccategory=$_GET['category'];
    
    $tdate=$_GET['tdate'];
    
    $insert="insert into complainant(c_name,c_add,c_city,c_contno,c_nature,con_officer,c_detail,officer_name,c_date,c_category,oldc_no,c_time,c_ndate,c_mode,tdate) 
             values('$cname','$caddress','$city','$ccontact','$nature','$c_officer','$summary','$cofficer','$cdate','$ccategory','$oldcomplain','$comtime','$ndate','$cmode','$tdate')";
    $db->query($insert);
    $regno=$db->insert_id();
    
    $select="select * from officer where o_id='$cofficer'";
    $res1=$db1->query($select);
    $rows1=$db1->fetch_array($res1);
    $offcontact = $rows1['officer_contno'];
    $offname = $rows1['officer_name'];
    $off_email=$rows1['email'];
    
    $select2="select * from con_officer where c_id='$c_officer'";
    $res2=$db1->query($select2);
    $rows2=$db1->fetch_array($res2);
    $con_contact = $rows2['officer_contno'];
    $conoff_name = $rows2['officer_name'];
    $conoff_email=$rows2['email'];
    
    $message1="Your%20Comp%20No.$regno/$ccategory/Comp%20Date:$cdate/Target%20Date:$tdate";
    $message2="Your%20Comp%20No.$regno/$ccategory/Comp%20Date:$cdate/Target%20Date:$tdate/$conoff_name-$con_contact/for%20more%20detail%20pls%20check%20your%20mail.";
    $message3="Your%20Comp%20No.$regno/$ccategory/Comp%20Date:$cdate/Target%20Date:$tdate/$offname-$offcontact/for%20more%20detail%20pls%20check%20your%20mail.";
    
    $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&to=$ccontact&msg=$message1";
    $file1=fopen("$url1","r");
    fclose("$file1");
    
    $url2="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&to=$ccontact&msg=$message1&to=$offcontact&msg=$message2";
    $file2=fopen("$url2","r");
    fclose("$file2");
    
    $url3="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&to=$ccontact&msg=$message1&to=$con_contact&msg=$message3";
    $file3=fopen("$url3","r");
    fclose("$file3");
    
    $headers = "MIME-Version: 1.0\r\n"; 
    $headers  .= "From: Webmaster<webmaster@cropsoft.com>\r\n";
    $headers .= "Content-type: text/html; charset=utf-8";
    
    if($off_email!="")
     {
       $off_to=$off_email;
       $off_subject="A new complain has been registered";
       $off_message="<html xmlns='http://www.w3.org/1999/xhtml' lang='hi'>
                      <head>
                      <title></title>
                      <meta http-equiv='content-type' content='text/html;charset=utf-8' />
                      <meta http-equiv='Content-Language' content='hi'>
                      <body>
                        <table width='100%'>
                         <tr>
                          <td>Summery</td>
                          <td>$summary</td>
                         </tr>
                        </table>
                      </body>
                      </html>";
       @mail($off_to,$off_subject,$off_message,$headers);
     }
     
     if($conoff_email!="")
     {
       $conoff_to=$conoff_email;
       $conoff_subject="A new complain has been registered";
       $conoff_message="<html xmlns='http://www.w3.org/1999/xhtml' lang='hi'>
                      <head>
                      <title></title>
                      <meta http-equiv='content-type' content='text/html;charset=utf-8' />
                      <meta http-equiv='Content-Language' content='hi'>
                      <body>
                        <table width='100%'>
                         <tr>
                          <td>Summery</td>
                          <td>$summary</td>
                         </tr>
                        </table>
                      </body>
                      </html>";
       @mail($conoff_to,$conoff_subject,$conoff_message,$headers);
     }
  
   echo"Your complain has been registered";
?>