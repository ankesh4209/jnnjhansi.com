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

 $query="select a.a_rdate,a.a_rno,b.d_off_contact,,b.d_soff_contact from tbl_automation as a, tbl_department as b where a.app_status=1 and a.a_department=b.d_id";
 $result=$db->query($query);
  while($row = $db->fetch_array($result))
  {
    $reg_date_15=$row['a_rdate']+15*24*60*60;
    $reg_date_25=$row['a_rdate']+25*24*60*60;
    $reg_date_30=$row['a_rdate']+30*24*60*60;
    $reg_date_31=$row['a_rdate']+31*24*60*60;
    $todaydate=mktime(0,0,0,date('m'),date('d'),date('Y'));
    $off_phone=$row['d_off_contact'];
    $soff_phone=$row['d_soff_contact'];
    $reg_new=$row['a_rno'];
    $msg_date=date('d-m-Y',$row['a_rdate']);
    $msg_trg_date=$row['a_rdate']+30*24*60*60;
    $msg_trg_date=date('d-m-Y',$msg_trg_date);
    if($todaydate==$reg_date_15)
    {
      $u_message="Reference%20No.%20$reg_new/Reg.%20Date%20$msg_date/Target%20Date%20$msg_trg_date/still%20pending%20Pls%20resolve%20within%2015%20days/For%20more%20detail%20Pls%20contact%20Jan%20Suchana%20Anubhag,%20Nagar%20Nigam%20Jhansi.";
      $url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$off_phone&msgtxt=$u_message";
      $file1=fopen("$url1","r");
      fclose("$file1");
    }
    elseif($todaydate==$reg_date_25)
    {
      $u_message="Reference%20No.%20$reg_new/Reg.%20Date%20$msg_date/Target%20Date%20$msg_trg_date/still%20pending%20Pls%20resolve%20within%205%20days/For%20more%20detail%20Pls%20contact%20Jan%20Suchana%20Anubhag,%20Nagar%20Nigam%20Jhansi.";
      $url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$off_phone&msgtxt=$u_message";
      $file1=fopen("$url1","r");
      fclose("$file1");
    
    }
    elseif($todaydate==$reg_date_30)
    {
      $u_message="Reference%20No.%20$reg_new/Reg.%20Date%20$msg_date/Target%20Date%20$msg_trg_date/Today%20is%20last%20days%20to%20resolve%20the%20Referance/For%20more%20detail%20Pls%20contact%20Jan%20Suchana%20Anubhag,%20Nagar%20Nigam%20Jhansi.";
      $url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$off_phone&msgtxt=$u_message";
      $file1=fopen("$url1","r");
      fclose("$file1");
    
    }
    elseif($todaydate==$reg_date_31)
    {
      $u_message="Reference%20No.%20$reg_new/Reg.%20Date%20$msg_date/Target%20Date%20$msg_trg_date/Still%20Pending.";
      
      $url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$soff_phone&msgtxt=$u_message";
      $file1=fopen("$url1","r");
      fclose("$file1");
      
      $url2="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=9935241173&msgtxt=$u_message";
      $file2=fopen("$url2","r");
      fclose("$file2");
      
      $url3="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=9411861236&msgtxt=$u_message";
      $file3=fopen("$url3","r");
      fclose("$file3");
    
    }
    else
    {
    
    }
    
  }

?>
