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

if($_POST['add']!='')
 {
   AddAnswer($db,$db1);   
 }

if($_POST['search']!='' or $_POST['add']!='')
{
  Getdetails($db,$db1);
  $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_answer_details.html");
}
else
{
  $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_answer.html");
}

$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function Getdetails($db,$db1)
 {  
    global $questions,$id,$select1,$select2,$ansdate,$name,$regno;
    $id=$_REQUEST['reg_id'];
    $query="select * from tbl_question where reg_id='$id' and answer='' order by q_id asc";
    $db->query($query);
		if($db->num_rows())
		{
		  
			while($rows = $db->fetch_array())
			{
			  $ansdate=$rows['date'];
			  if($ansdate!="" or $ansdate!=0)
			   $ansdate=date('d/m/Y',$ansdate);
			  else
			   $ansdate="";
        $qus=stripslashes($rows['question']);
			  $ans=stripslashes($rows['answer']);
			  $questions.="<tr>
                     <td valign='top'><span style='font-family:Kruti Dev 011;font-size:18px;'>iz’u</span></td>
                     <td><textarea rows='3' cols='68' style='font-family:Kruti Dev 011;font-size:18px;' name='question[]' readonly>$qus</textarea></td>
                    </tr>
                    <tr>
                     <td valign='top'><span style='font-family:Kruti Dev 011;font-size:18px;'>mRrj</span></td>
                     <td><textarea rows='3' cols='68' style='font-family:Kruti Dev 011;font-size:18px;' name='answer[]'>$ans</textarea></td>
                    </tr>";
			
			}
		}
		
	  $select="select app_status,a_rno,a_name  from tbl_automation where a_rno ='$id'";
	  $result=$db1->query($select);
	  $rows1 = $db1->fetch_array($result);
	  $app_status=$rows1['app_status'];
	  $name=$rows1['a_name'];
	  $regno=$rows1['a_rno'];
	  if($app_status==1)
	   {
       $select1="selected";
       $select2="";
     }
     else
	   {
       $select1="";
       $select2="selected";
     }
	  
 }
 
function AddAnswer($db,$db1)
 {
  
   $aut_id=$_REQUEST['reg_id'];
   $delete="delete from tbl_question where reg_id='$aut_id'";
   $db->query($delete);
   
    $question=$_POST['question'];
    $answer=$_POST['answer'];
    $ansdate=$_POST['ansdate'];
    $ansdate=explode('/',$ansdate);
    $ans_date=mktime(0,0,0,$ansdate[1],$ansdate[0],$ansdate[2]);
    for($i=0;$i<=count($question);$i++)
     {
       if($question[$i]!="")
        {
          $questions=addslashes($question[$i]);
          $answers=addslashes($answer[$i]);
        $insert1="insert into tbl_question(reg_id,question,answer,date) values('$aut_id','$questions','$answers','$ans_date')";
          $db->query($insert1);
        }
     }
   
    $status=$_POST['status'];
    $update="update tbl_automation  set app_status='$status' where a_rno ='$aut_id'";
    $db->query($update);
    
     $sql1="select b.d_off_contact from tbl_automation as a,tbl_department as b where a.a_rno='$aut_id' and a.a_department=b.d_id";
     $result1=$db1->query($sql1);
     $row1 = $db1->fetch_array($result1);
     $off_phone=$row1['d_off_contact'];
     
     $res_date=mktime(0,0,0,date('m'),date('d'),date('Y'));
     $res_date=date('d-m-Y',$res_date);
     
     if($status==2 and $off_phone!="")
     {
       $u_message="Reference%20No.%20$aut_id/Resolve%20by%20you%20on%20date%20$res_date/To%20get%20more%20detail%20Pls%20contact%20Jan%20Suchana%20Anubhag,%20Nagar%20Nigam%20Jhansi.";
       $url1="http://dndopen.dove-sms.com/TransSMS/SMSAPI.jsp?username=JNNJHS&password=JNNJHS&sendername=JNNJHS&mobileno=$off_phone&message=$u_message";
	   //$url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jnnjhansi@gmail.com:Jhansi0510&senderID=JNN-JHS&receipientno=$off_phone&msgtxt=$u_message";
       $file1=fopen("$url1","r");
       fclose("$file1");
     }
 
 }
 
 
?>
