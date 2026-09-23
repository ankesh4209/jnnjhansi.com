<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

if($_POST['submit']!="")
{
    $regid=$_REQUEST['regid'];
    
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
    $department=addslashes($_POST['department']);
    $target_date=mktime(0,0,0,$regdate[1],$regdate[0]+25,$regdate[2]);
    $msg_date=date('d-m-Y',$reg_date);
    $msg_trg_date=date('d-m-Y',$target_date);
    $reg_new=$_POST['regno'];
    
    $insert="update tbl_automation set a_rno='$reg_new',a_rdate='$reg_date',a_newrdate='$new_regdate',a_name='$name',a_fname='$fname',a_address='$address',
             a_contactno='$phone',a_resipt='$resipt',a_subject='$subject',a_department='$department'  where a_id='$regid'";
    $db->query($insert);
      
    $delete="delete from tbl_question where reg_id='$reg_new'";
    $db1->query($delete);
    
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
    
    echo "<script type='text/javascript'>
      <!-- 
       window.location = 'automation.php?msg=e_succ'
      //-->
      </script>"; 

}


$id=$_GET['aid'];
Getdetails($db,$db1,$id);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/edit_automation.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function  Getdetails($db,$db1,$id)
{
  Global $reg,$reg_date,$name,$fname,$address,$contactno,$resipt,$subject,$department,$question,$newreg_date;
 
  $sql="select * from tbl_automation where a_id='$id'";
  $res=$db->query($sql);
  $rows = $db->fetch_array($res);
  
  $reg=$rows['a_rno'];
  $reg_date=$rows['a_rdate'];
  $reg_date=date('d/m/Y',$reg_date);
  $newreg_date=$rows['a_newrdate'];
  $newreg_date=date('d/m/Y',$newreg_date);
  $name=stripslashes($rows['a_name']);
  $fname=stripslashes($rows['a_fname']);
  $address=stripslashes($rows['a_address']);
  $contactno=$rows['a_contactno'];
  $resipt=stripslashes($rows['a_resipt']);
  $subject=stripslashes($rows['a_subject']);
  $dep_id=$rows['a_department'];
  
  
  $sql1="select * from tbl_department";
  $result1=$db1->query($sql1);
  while($row1 = $db1->fetch_array($result1))
  {
    $did=$row1['d_id'];
    $dname="<span style='font-family:Kruti Dev 011;font-size:18px;'>".stripslashes($row1['d_name'])."</span>";
    if($did==$dep_id)
     $department.="<option value='$did' selected>$dname</option>";
    else
     $department.="<option value='$did' >$dname</option>";
  }
	
  $sql2="select * from tbl_question where reg_id='$reg' order by q_id asc";
  $result2=$db1->query($sql2);
  while($row2 = $db1->fetch_array($result2))
  {
    //$qid=$row1[' q_id '];
    $question.="<textarea rows='3' cols='48' style='font-family:Kruti Dev 011;font-size:18px;' name='question[]'>".stripslashes($row2['question'])."</textarea><br>";
    //$question.="<option value='$did'>$dname</option>";
  }  
}


?>
