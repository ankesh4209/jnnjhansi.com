<?php session_start(); ?>
<?php	

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$id=$_GET['aid'];
GetLetter($db,$db1,$id);
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/print_resipt.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();


function GetLetter($db,$db1,$id)
 {
    global $rno,$name,$fname,$address,$reg_date,$subject,$question,$answer_date,$newreg_date,$d_name,$currentyear,$nextyear;
    
    $sql="select * from tbl_automation where a_id='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
    $rno=$rows['a_rno'];
    $reg_date=$rows['a_rdate'];
    $reg_date=date('j M Y',$reg_date);
    $newreg_date=$rows['a_newrdate'];
    $newreg_date=date('j M Y',$newreg_date);
    $subject=$rows['a_subject'];
    $name=$rows['a_name'];
    $fname=$rows['a_fname'];
    $address=$rows['a_address'];
    $cdate=$rows['a_cdate'];
    $camount=$rows['a_camount'];
    $department=$rows['a_department'];
    if(date('n')>3) {
     $currentyear=date('Y');
     $nextyear=$currentyear+1;
    } else {
     $currentyear=date('Y')-1;
     $nextyear=$currentyear+1;
    }
    
    $sql1="select d_name  from tbl_department  where d_id ='$department'";
    $res1=$db1->query($sql1);
    $rows1 = $db1->fetch_array($res1);
    $d_name=$rows1['d_name'];
    
    $i=1;
    $sql2="select * from tbl_question where reg_id='$rno' or reg_id='$rno-A' or reg_id='$rno-B' or reg_id='$rno-C' or reg_id='$rno-D' or reg_id='$rno-E' or reg_id='$rno-F' order by q_id asc";
    $result2=$db1->query($sql2);
    while($row2 = $db1->fetch_array($result2))
    {
      $answer_date=$row2['date'];
      $answer_date=date('j M Y',$answer_date);
      $question.="<tr><td valign='top'><span style='font-family:Kruti Dev 011;font-size:18px;'>".$row2['question']."</span></td><td valign='top'><span style='font-family:Kruti Dev 011;font-size:18px;'>".$row2['answer']."</span></td></tr>";
      //$question.="<option value='$did'>$dname</option>";
      $i++;
    }

 }
?>
