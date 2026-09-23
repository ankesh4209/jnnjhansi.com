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
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/print_resipt1.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();


function GetLetter($db,$db1,$id)
 {
    global $rno,$name,$address,$reg_date,$newreg_date,$subject,$question,$d_name,$b,$currentyear,$nextyear;
    
    $sql="select * from tbl_automation where a_id='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
    $rno=$rows['a_rno'];
    $reg_date=$rows['a_rdate'];
    $reg_date=date('j M Y',$reg_date);
    $newreg_date=$rows['a_newrdate'];
    $newreg_date=date('j M Y',$newreg_date);
    $subject=stripslashes($rows['a_subject']);
    $name=stripslashes($rows['a_name']);
    $address=stripslashes($rows['a_address']);
    $cdate=$rows['a_cdate'];
    $camount=stripslashes($rows['a_camount']);
    $department=stripslashes($rows['a_department']);
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
    $sql2="select * from tbl_question where reg_id='$rno' order by q_id asc";
    $result2=$db1->query($sql2);
    while($row2 = $db1->fetch_array($result2))
    {
      $question.="<tr><td><span style='font-family:Kruti Dev 011;font-size:18px;'>".stripslashes($row2['question'])."</span></td><td>&nbsp;</td></tr>";
      $i++;
    }
   $b=$i-1;
 }
?>
