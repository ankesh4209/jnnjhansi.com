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

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());


ViewDepartment($db);

if($_POST['submit']!='')
 {
       $result=adddetails($db,$db1);
       if($result)
	 {
	   echo "<script type='text/javascript'>
        <!-- 
         window.location = 'automation.php?msg=succ'
        //-->
        </script>";   
	 }
	 else
	 {   global $regno_msg;
		 $regno_msg="Registration no already exist.";
		  
	 }
 }

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_automatiom.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function adddetails($db,$db1)
 {
     $reg_new=$_POST['regno'];

	// $reg_count="select count(*) as total from tbl_automation where a_rno='$reg_new'";
    // $db->query($reg_count);
	// $reg_row = $db->fetch_assoc();
    // if($reg_row['total']=='0')
	 //{

	

		  $regdate=$_POST['regdate'];
		  $regdate=explode('/',$regdate);
		  $reg_date=mktime(0,0,0,$regdate[1],$regdate[0],$regdate[2]);
		  
		  $newregdate=$_POST['newregdate'];
		  $newregdate=explode('/',$newregdate);
		  $new_regdate=mktime(0,0,0,$newregdate[1],$newregdate[0],$newregdate[2]);
		  
		  $name=addslashes($_POST['aname']);
		  $fname=addslashes($_POST['fname']);
		  $address=addslashes($_POST['address']);
		  $phone=$_POST['phone'];
		  $resipt=addslashes($_POST['resipt']);
		  $subject=addslashes($_POST['subject']);
		  $department=$_POST['department'];
		  $target_date=mktime(0,0,0,$regdate[1],$regdate[0]+25,$regdate[2]);
		  $msg_date=date('d-m-Y',$reg_date);
		  $msg_trg_date=date('d-m-Y',$target_date);
		 
		  
		  $insert="insert into tbl_automation(a_rno,a_rdate,a_newrdate,a_name,a_fname,a_address,a_contactno,a_resipt,a_subject,a_department,app_status) 
				   values('$reg_new','$reg_date','$new_regdate','$name','$fname','$address','$phone','$resipt','$subject','$department','1')";
		  $db->query($insert);
		  $sid=$db->insert_id();
		  
		  $question=$_POST['question'];
		  for($i=0;$i<=count($question);$i++)
		   {
			 if($question[$i]!="")
			  {
				$questions=addslashes($question[$i]);
				$insert1="insert into tbl_question(reg_id,question) values('$reg_new','$questions')";
				$db->query($insert1);
			  }
		   }
		
		 $sql1="select * from tbl_department where d_id='$department'";
		 $result1=$db1->query($sql1);
		 $row1 = $db1->fetch_array($result1);
		 $off_phone=$row1['d_off_contact'];
		 
		 if($off_phone!="")
		 {
		  $u_message="Reference%20No.%20$reg_new/Reg.%20Date%20$msg_date/Target%20Date%20$msg_trg_date/to%20get%20more%20detail%20Pls%20contact%20Jan%20Suchana%20Anubhag,%20Nagar%20Nigam%20Jhansi.";
		  $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&mobileno=$off_phone&message=$u_message";
		  //$url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$off_phone&msgtxt=$u_message";
		  $file1=fopen("$url1","r");
		  fclose("$file1");
		 }
		 return true;
	 //}
	// else
	// {
		//return false;
	 //}
     /* $user_message="Your%20Map%20has%20been%20registered%20in%20JDA%20for%20Approval/Reg.%20Date%20$message_date/Map%20No%20$reg_new/JDA%20JE%20Name%20$je_name-$je_no";
      $je_message="A%20new%20map%20is%20registered/Reg.%20Date%20$message_date/Map%20no%20$reg_new/Contact%20Applicant%20$contact_no";
      $other_message="If%20JDA%20JE%20$je_name%20does%20not%20come%20for%20site%20inspection%20within%20a%20Week,%20Please%20contact%20Zone%20I/C%20$ae_name-$ae_no/Secratery%209506034777";
    
     $url2="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&receipientno=$contact_no&msgtxt=$user_message";
     $file2=fopen("$url2","r");
     fclose("$file2");
     
     $url3="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&receipientno=$contact_no&msgtxt=$other_message";
     $file3=fopen("$url3","r");
     fclose("$file3");
    
     $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&receipientno=$je_no&msgtxt=$je_message";
     $file1=fopen("$url1","r");
     fclose("$file1"); */
  
 }
 
 function ViewDepartment($db)
 {
   global $department;
    $sql="select * from tbl_department";
    $result=$db->query($sql);
    while($row = $db->fetch_array($result))
	  {
      $did=$row['d_id'];
      $dname="<span style='font-family:Kruti Dev 011;font-size:18px;'>".$row['d_name']."</span>";
	    $department.="<option value='$did'>$dname</option>";
	  }
 }
?>