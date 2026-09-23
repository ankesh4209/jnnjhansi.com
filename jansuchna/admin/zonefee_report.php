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

GetFee($db);
Getzone($db);

if($_POST['submit']!="")
{
 $feetype=$_POST['feetype'];
 $zone=$_POST['zone'];
 $sdate=$_POST['sdate'];
 $edate=$_POST['edate'];
 Getresult($db,$db1,$feetype,$zone,$sdate,$edate);
 $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/zonefee_result.html");
}
else
{
 $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/zonefee_report.html");
}


$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetFee($db)
 {
   global $fee_list;
   $sql="select * from tbl_fee";
   $res=$db->query($sql);
   while($rows=$db->fetch_array($res))
    {
      $fid=$rows['f_id'];
      $fname=$rows['f_name'];
      $fee_list.="<option value='$fid'>$fname</option>";
    }
 }
 
 function Getzone($db)
 {
   global $zone_list;
   $sql="select * from tbl_zone";
   $res=$db->query($sql);
   while($rows=$db->fetch_array($res))
    {
      $zid=$rows['z_id'];
      $zname=$rows['z_name'];
      $zone_list.="<option value='$zid'>$zname</option>";
    }
 }
function Getresult($db,$db1,$feetype,$zone,$sdate,$edate)
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
   
   
   if($zone==1000)
    {
     $sql="select * from tbl_zone";
    }
   else
    {
     $sql="select * from tbl_zone where z_id='$zone'";
    }
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
