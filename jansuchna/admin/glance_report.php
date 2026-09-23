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
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

Getdepartment($db);

if($_POST['submit']!="")
{
 $department=$_POST['department'];
 $sdate=$_POST['sdate'];
 $edate=$_POST['edate'];
 Getresult($db,$db1,$db2,$department,$sdate,$edate);
 $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/glance_result.html");
}
else
{
 $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/glance_report.html");
}


$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function Getdepartment($db)
 {
   global $dep_list;
   $sql="select * from tbl_department";
   $res=$db->query($sql);
   while($rows=$db->fetch_array($res))
    {
      $did=$rows['d_id'];
      $dname=$rows['d_name'];
      $dep_list.="<option value='$did' >$dname</option>";
    }
 }
 
function Getresult($db,$db1,$db2,$department,$sdate,$edate)
 {
   global $total_result,$report_message,$department,$sdate,$edate,$grand_total;
   $sdate1=explode("/",$sdate);
   $s_date=mktime(0,0,0,$sdate1[1],$sdate1[0],$sdate1[2]);
   
   $edate1=explode("/",$edate);
   $e_date=mktime(0,0,0,$edate1[1],$edate1[0],$edate1[2]);
   
   $report_message="<td>Result from ".date('j M Y',$s_date)." to ".date('j M Y',$e_date)."</td>";
   
   if($department==1000)
    {
      $sql_total1="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date'";
      $res_total1=$db2->query($sql_total1);
      $total_map1=$db2->num_rows();
      
      $sql_pending1="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and app_status=1";
      $res_pending1=$db2->query($sql_pending1);
      $total_pending1=$db2->num_rows();
      
      $sql_complete1="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and app_status=2";
      $res_complete1=$db2->query($sql_complete1);
      $total_complete1=$db2->num_rows();
      
      $sql_noaction1="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and app_status=3";
      $res_noaction1=$db2->query($sql_noaction1);
      $total_noaction1=$db2->num_rows();
      
      $grand_total="<tr>
                      <td align='center'  align='right'><b>Total</b></td>
                      <td align='center'>$total_map1</td>
                      <td align='center'>$total_pending1</td>
                      <td align='center'>$total_complete1</td>
                      <td align='center'>$total_noaction1</td>
                     </tr>";
    }
   
   if($department==1000)
    {
     $sql="select * from tbl_department";
    }
   else
    {
     $sql="select * from tbl_department where d_id='$department'";
    }
    
   $res=$db->query($sql);
   while($rows=$db->fetch_array($res))
    {
      $did=$rows['d_id'];
      $dname=$rows['d_name'];
      
      $sql_total="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and a_department='$did'";
      $res_total=$db1->query($sql_total);
      $total_map=$db1->num_rows();
      
      $sql_pending="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and a_department='$did' and app_status=1";
      $res_pending=$db1->query($sql_pending);
      $total_pending=$db1->num_rows();
      
      $sql_complete="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and a_department='$did' and app_status=2";
      $res_complete=$db1->query($sql_complete);
      $total_complete=$db1->num_rows();
      
      $sql_noaction="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and a_department='$did' and app_status=3";
      $res_noaction=$db1->query($sql_noaction);
      $total_noaction=$db1->num_rows();
      
      $total_result.="<tr>
                      <td align='center' style='font-family:Kruti Dev 011;font-size:18px;'>$dname</td>
                      <td align='center'>$total_map</td>
                      <td align='center'>$total_pending</td>
                      <td align='center'>$total_complete</td>
                      <td align='center'>$total_noaction</td>
                     </tr>";
      
    }
   
 }
 
 
?>
