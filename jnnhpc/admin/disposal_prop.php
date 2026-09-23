<?php session_start(); ?>


<?php

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");
error_reporting(0);
$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());


if($_POST['submit']!='')
 {

   editcomplainant($db,$db1);
   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'proposals.php?d=1'
        //-->
        </script>";  
 }

$cid=$_GET['cid'];
complainantdetails($db,$cid,$db1,$db2);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/disposal_prop.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function editcomplainant($db,$db1)
{
  $cid=$_REQUEST['cid'];
  $ddate2=$_POST['ddate'];
  $ddate1=$_POST['ddate'];
  $ddate1=explode("/",$ddate1);
  $ddate=mktime(0,0,0,$ddate1['1'],$ddate1['0'],$ddate1['2']);
  $disposaltext=$_POST['disposaltext'];
  $update="update complainant set d_date='$ddate',disposaltext='$disposaltext',status='1' where c_id='$cid'"; 
  $db->query($update);
  
  $cdate=$_REQUEST['cdate'];
  $tdate=$_REQUEST['tdate'];
  $ccategory=$_REQUEST['category'];
  $cofficer=$_REQUEST['officer1'];
  $c_officer=$_REQUEST['c_officer1'];
  $cmode=$_REQUEST['cmode'];
  $cname=$_REQUEST['cname'];
  $city=$_REQUEST['city'];
  $caddress=$_REQUEST['address'];
  $nature=$_REQUEST['nature'];
  $summary=$_REQUEST['summary'];
  $cmodetype=$_REQUEST['cmodetype'];
  $add_phone=$_REQUEST['add_phone'];
  
    
    $select2="select * from con_officer where c_id='$c_officer'";
    $res2=$db1->query($select2);
    $rows2=$db1->fetch_array($res2);
    $con_contact = $rows2['officer_contno'];
    $conoff_name = $rows2['off_desi'];
    $conoff_email=$rows2['email'];
  
    $message1="Your%20Comp%20No.%20$cid%20has%20been%20disposed/to%20get%20more%20details%20pls.%20contact%20Jan%20Pratinidhi/Prasashan%20Shikayat%20Sandarbh,%20Jhansi%20Nagar%20Nigam%20Jhansi";
    $message2="Comp%20No.$cid%20has%20been%20disposed%20and%20closed";
 
     $contactno=$_REQUEST['contact'];
   
     $url1="http://dndopen.dove-sms.com/TransSMS/SMSAPI.jsp?username=JNNJHS&password=JNNJHS&sendername=JNNJHS&mobileno=$contactno&message=$message1";
    //$url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$contactno&msgtxt=$message1";
    //$file1=fopen($url1,"r");
    //fclose("$file1");
  
    $url2="http://dndopen.dove-sms.com/TransSMS/SMSAPI.jsp?username=JNNJHS&password=JNNJHS&sendername=JNNJHS&mobileno=$con_contact&message=$message2";
	//$url2="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$con_contact&msgtxt=$message2";
    //$file2=fopen("$url2","r");
    //fclose("$file2");
     
}

function complainantdetails($db,$cid,$db1,$db2)
{
   global $cid,$cname,$address,$contact,$regno,$ctime,$contact1,$summary,$officer,$officer1,$c_officer1,$cdate,$category,$cmode,$ndate,$newspname,$oldcno,
          $select1,$select2,$select3,$select4,$select5,$tdate,$remark,$more_officer,$tdate,$city,$concern_officer;
    global $scmode1,$scmode2,$scmode3,$scmode4,$scmode5,$cmodetype,$add_phone1,$nature,$add_phone;
          
     $sql="select * from complainant where c_id='$cid'";
     $db->query($sql);
     $rows = $db->fetch_array();
     $cid=$rows['c_id'];
     $cname=stripslashes($rows['c_name']);
     $address=stripslashes($rows['c_add']);
     $contact1=stripslashes($rows['c_contno']);
     $add_phone=$rows['add_phone'];
     
     $regno=stripslashes($rows['c_regno']);
     $summary=stripslashes($rows['c_detail']);
     $cdate1=$rows['c_date'];
     $cdate=date('d/m/Y',$cdate1);
     $remark=stripslashes($rows['c_remark']);
     $oldcno=$rows['oldc_no'];
     $ctime=$rows['c_time'];
     $ndate=$rows['c_ndate'];
     $tdate=$rows['tdate'];
     //$city=$rows['c_city'];
     
     $fax=$rows['fax'];
     $newspapername=$rows['c_newspapername'];
     $televisionname=$rows['c_televisionname'];
     //$nature=$rows['c_nature'];
     
     //-------------------------- city ----------------------
       $city1=$rows['c_city'];
       $sql3="select city_name from city where city_id='$city1'";
       $res3=$db1->query($sql3);
       $rows3=$db1->fetch_array($res3);
       $city=$rows3['city_name'];
       
     //-------------------------- nature -------------------
       $nature1=$rows['department'];
       $sql4="select nature_name from nature where nature_id='$nature1'";
       $res4=$db1->query($sql4);
       $rows4=$db1->fetch_array($res4);
       $nature=$rows4['nature_name'];
       
    //------------------------concern officer-----------------------
      $con_officer=$rows['con_officer'];
      $sql2="select * from con_officer where c_id='$con_officer'";
      $res2=$db2->query($sql2);
      $rows2=$db2->fetch_array();
      $c_officer1=$rows2['c_id'];
      $concern_officer = $rows2['off_desi'];
         
   
   //------------------------------C mode------------------------
     $cmode=$rows['c_mode'];
     if($cmode=='Newspaper')
      $scmode1="selected";
     else
      $scmode1="";
     
     if($cmode=='Fax')
      $scmode2="selected";
     else
      $scmode2="";
      
      if($cmode=='Television')
      $scmode3="selected";
     else
      $scmode3="";
      
      if($cmode=='Phone')
      $scmode4="selected";
     else
      $scmode4="";
      
      if($cmode=='Other')
      $scmode5="selected";
     else
      $scmode5="";
      
      if($cmode=='Phone')
       {
         $cmodetype="<tr>
                      <td>Mobile/Telephones :</td>
                      <td>$contact1</td>
                     </tr>";
       }
      elseif($cmode=='Fax')
       {
         $cmodetype="<tr>
                      <td>Fax:</td>
                      <td>$fax</td>
                     </tr>";
       }
      elseif($cmode=='Newspaper')
       {
         $newspaperdate=$rows['c_ndate'];
         $newspaperdate=date('d-m-Y',$newspaperdate);
         $cmodetype="<tr>
                      <td>Newspaper Name:</td>
                      <td>$newspapername  &nbsp;&nbsp;&nbsp;Newspaper Date : &nbsp;&nbsp; $newspaperdate</td>
                     </tr>";
       }
      elseif($cmode=='Television')
       {
         $televisiondate=$rows['c_tdate'];
         $televisiondate=date('d-m-Y',$televisiondate);
         $cmodetype="<tr>
                      <td>Television Name:</td>
                      <td>$televisionname &nbsp;&nbsp;&nbsp;Television Date : &nbsp;&nbsp; $televisiondate</td>
                     </tr>";
       }
      else
       {
         $cmodetype="";
       }
     
     $add_phone=$rows['add_phone'];
     if($add_phone!="")
      {
       $add_phone1="<tr>
                      <td>Additional Phone:</td>
                      <td>$add_phone</td>
                     </tr>";
      }
   //---------------------------------category-------------------
   
   $category=stripslashes($rows['c_category']);
   if($category=='A')
     $select1="selected";
   else
     $select1="";
     
   if($category=='B')
     $select2="selected";
   else
     $select2="";
     
   if($category=='C')
     $select3="selected";
   else
     $select3="";
     
   if($category=='D')
     $select4="selected";
   else
     $select4="";
     
   if($category=='T')
     $select5="selected";
   else
     $select5="";
     
  //------------------------- target date -------------------------------
  
   if($category=='D')
     { 
       $tdate1="No time bound";
       $tdate="No time bound";
     }
    else
     {
       $tdate1=$rows['tdate'];
       $tdate=date('d-m-Y',$tdate1);
     }
     
     
}


?>
