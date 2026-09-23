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
GetResipt($db,$db1,$id);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/print.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();

function GetResipt($db,$db1,$id)
 {
    global $rno,$name,$address,$challanno,$cdate,$arazi,$mauja_name,$camount,$zname,$reg_date,$expdate,$aname,$reopen_status;
    
    $sql="select * from tbl_automation where a_id='$id'";
    $res=$db->query($sql);
    $rows = $db->fetch_array($res);
    
    $rno=$rows['a_rno'];
    $zone=$rows['a_zone'];
    $name=ucwords($rows['a_name']);
    $address=$rows['a_address'];
    $challanno=$rows['a_challanno'];
    $cdate=$rows['a_cdate'];
    $cdate=explode("/",$cdate);
    $cdate=mktime(0,0,0,$cdate[1],$cdate[0],$cdate[2]);
    $cdate=date('j M Y',$cdate);
    $camount=$rows['a_camount'];
    $reg_date=date("d/m/Y");  
    $pbusage=$rows['a_pbusage'];
    $area=$rows['a_area'];
    $arazi=$rows['a_arazi'];
    $mauja=$rows['a_mauja'];
    $reopen=$rows['reopen_status'];
    if($reopen==1)
     {
       $reopen_status="(Reopen)";
     }
    
    $sql1="select z_name from tbl_zone where z_id='$zone'";
    $res1=$db1->query($sql1);
    $rows1=$db1->fetch_array($res1);
    $zname=$rows1['z_name'];
    
    $sql2="select pb_days from tbl_pbusage where pb_id='$pbusage'";
    $res2=$db1->query($sql2);
    $rows2=$db1->fetch_array($res2);
    $days=$rows2['pb_days'];
    
    $sql3="select a_name from tbl_area where a_id='$area'";
    $res3=$db1->query($sql3);
    $rows3 = $db1->fetch_array($res3);
    $aname=ucwords($rows3['a_name']);
    
    $sql4="select mauja_name from tbl_mauja where mauja_id='$mauja'";
    $res4=$db1->query($sql4);
    $rows4 = $db1->fetch_array($res4);
    $mauja_name=$rows4['mauja_name'];

    $expdate=mktime(0,0,0,date('m'),date('d')+$days,date('Y'));
    $expdate=date('d/m/Y',$expdate);
 }

?>
