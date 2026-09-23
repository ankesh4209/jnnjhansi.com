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

Getdepartment($db);

if($_POST['submit']!="")
{
 $department=$_POST['department'];
 $sdate=$_POST['sdate'];
 $edate=$_POST['edate'];
 Getresult($db,$db1,$department,$sdate,$edate);
 $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/zone_result.html");
}
else
{
 $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/zone_report.html");
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
 
function Getresult($db,$db1,$department,$sdate,$edate)
 {
   global $total_result,$report_message,$department,$sdate,$edate;
   $sdate1=explode("/",$sdate);
   $s_date=mktime(0,0,0,$sdate1[1],$sdate1[0],$sdate1[2]);
   
   $edate1=explode("/",$edate);
   $e_date=mktime(0,0,0,$edate1[1],$edate1[0],$edate1[2]);
   
   $report_message="<td>Result from ".date('j M Y',$s_date)." to ".date('j M Y',$e_date)."</td>";
   
   if($department==1000)
    {
     $sql="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date'  order by a_rdate desc";
    }
   else
    {
     $sql="select * from tbl_automation where a_rdate>='$s_date' and a_rdate<='$e_date' and a_department='$department' order by a_rdate desc";
    }
    
   $res=$db->query($sql);
   if($db->num_rows()>0)
    {$slno=1;
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
         
        $total_result.="<tr><td align='center'>$slno</td>
                        <td align='center' style='font-family:Kruti Dev 011;font-size:18px;'>$dname</td>
                        <td align='center'>$reg_no</td>
                        <td align='center'>$reg_date</td>
                        <td align='center' style='font-family:Kruti Dev 011;font-size:18px;'>$name</td>
                        <td align='center'>$contact_no</td>
                        <td align='center'>$reg_status</td>
                       </tr>";
        
       $slno++;
	  }
    }
    else
    {
      $total_result.="<tr><td colspan='6' align='center'>No Result Found</td></tr>";
    }
   
 }
 
 
?>
