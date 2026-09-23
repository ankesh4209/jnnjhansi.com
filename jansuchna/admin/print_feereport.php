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
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

 $feetype=$_GET['feetype'];
 $sdate=$_GET['sdate'];
 $edate=$_GET['edate'];
 Getresult($db,$db1,$feetype,$sdate,$edate);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/print_feereport.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();

function Getresult($db,$db1,$feetype,$sdate,$edate)
 {
   global $total_result,$report_message,$feetype,$sdate,$edate;
   $sdate1=explode("/",$sdate);
   $s_date=mktime(0,0,0,$sdate1[1],$sdate1[0],$sdate1[2]);
   
   $edate1=explode("/",$edate);
   $e_date=mktime(0,0,0,$edate1[1],$edate1[0],$edate1[2]);
   
   //-------------- get fee name ------------------------------
    $sql_fee="select * from tbl_fee where f_id='$feetype'";
    $res_fee=$db1->query($sql_fee);
    $rows_fee=$db1->fetch_array($res_fee);
    $feename=$rows_fee['f_name'];
   
   $report_message="<td>Result from ".date('j M Y',$s_date)." to ".date('j M Y',$e_date)." for $feename</td>";
   
   
   $sql="select * from tbl_zone";
   $res=$db->query($sql);
   while($rows=$db->fetch_array($res))
    {
      $zid=$rows['z_id'];
      $zname=$rows['z_name'];
      $aename=$rows['z_aename'];
      $fee_total=0;
      $sql_total="select a.*,b.fee_amount from tbl_automation as a, tbl_feedetails as b where b.fee_date>='$s_date' and b.fee_date<='$e_date' 
                  and a.a_zone='$zid' and a.a_id=b.reg_id and b.fees_id='$feetype'";
      $res_total=$db1->query($sql_total);
      while($row_total=$db1->fetch_array($res_total))
       {
         $fee_total+=$row_total['fee_amount'];
       }
      $total_result.="<tr>
                      <td align='center'>$zname</td>
                      <td align='center'>$fee_total</td>
                     </tr>";
      
    }
   
 }
 
 
 
?>
