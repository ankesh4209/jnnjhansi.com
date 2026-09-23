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

 $zone=$_GET['zone'];
 $sdate=$_GET['sdate'];
 $edate=$_GET['edate'];
 Getresult($db,$db1,$zone,$sdate,$edate);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/print_zone.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();

function Getresult($db,$db1,$zone,$sdate,$edate)
 {
   global $total_result,$report_message,$zone,$sdate,$edate;
   $sdate1=explode("/",$sdate);
   $s_date=mktime(0,0,0,$sdate1[1],$sdate1[0],$sdate1[2]);
   
   $edate1=explode("/",$edate);
   $e_date=mktime(0,0,0,$edate1[1],$edate1[0],$edate1[2]);
   
   $report_message="<td>Result from ".date('j M Y',$s_date)." to ".date('j M Y',$e_date)."</td>";
   
   if($zone==1000)
    {
     $sql="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date'";
    }
   else
    {
     $sql="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and a_department ='$zone'";
    }
    
   $res=$db->query($sql);
   if($db->num_rows()>0)
    {
     while($rows=$db->fetch_array($res))
      {
        $reg_no=$rows['a_rno'];
        $reg_date=$rows['a_rdate'];
        $reg_date=date('j M Y',$reg_date);
        //-------------get Zone-----------------------------
        $did=$rows['a_department'];
        $sql1="select * from tbl_department where d_id='$did'";
        $res1=$db1->query($sql1);
        $rows1=$db1->fetch_array($res1);
        $dname=$rows1['d_name'];
        
        $name=$rows['a_name'];
        $contact_no=$rows['a_contactno'];
        
        $status=$rows['app_status'];
        if($status==1)
         $reg_status="Pending";
        elseif($status==2)
         $reg_status="Complete";
        elseif($status==3)
         $reg_status="No Action Taken";
         
        $total_result.="<tr>
                        <td align='center' style='font-family:Kruti Dev 011;font-size:18px;'>$dname</td>
                        <td align='center'>$reg_no</td>
                        <td align='center'>$reg_date</td>
                        <td align='center' style='font-family:Kruti Dev 011;font-size:18px;'>$name</td>
                        <td align='center'>$contact_no</td>
                        <td align='center'>$reg_status</td>
                       </tr>";
        
      }
    }
    else
    {
      $total_result.="<tr><td colspan='6' align='center'>No Result Found</td></tr>";
    }
   
 }
 
 
 
?>
