<?php session_start(); ?>
<?php 
 if ($_SESSION['username']=='')
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


$cid=$_GET['cid'];

complaintviews($db,$db1,$cid);
//studentfee($db,$stid);
    
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/complaintviewnew.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();



function complaintviews($db,$db1,$cid)
{
   global $cid,$cname,$dis,$disposal_text,$ccontno1,$caddress,$ccontno,$c_regno,$cdetail,$officer,$departname,
          $cdate,$ccategory,$ctime,$cnewspaper,$cmode,$tdate,$con_officer;
   
   $sql="select * from complainant where c_id='$cid'";
   $db->query($sql);
   $rows = $db->fetch_array();
   
   $cid=$rows['c_id'];
   $cname=stripslashes($rows['c_name']);
   $caddress=stripslashes($rows['c_add']);
   $ccontno1=$rows['c_contno'];
   if($ccontno1=="")
    {
      $ccontno1="Not Available";
    }
   $c_regno=stripslashes($rows['c_regno']);
   $cdetail=stripslashes($rows['c_detail']);
   $officername=$rows['con_officer'];
   $departname=stripslashes($rows['d_name']);
   $cdate1=$rows['c_date'];
   $cdate=date('d/m/Y',$cdate1);
   $ccategory=$rows['c_category'];
   $ctime=$rows['c_time'];
   //$cnewspaper=$rows['c_newspaper'];
   $cmode=$rows['c_mode'];
   
   if($ccategory=='D')
     { 
       $tdate1="No time bound";
       $tdate="No time bound";
     }
    else
     {
       $tdate1=$rows['tdate'];
       $tdate=date('d-m-Y',$tdate1);
     }
   
   if($dis==1)
    {
      $ddate=date('d-m-Y',$rows['d_date']);
      $comment=$rows['disposaltext'];
      $disposal_text="<tr>
                        <td width='200'>Disposal Date:</td>  
                        <td align='left'>$ddate</td> 
                      </tr>
                      <tr>
                        <td width='200'>Comment:</td>  
                        <td align='left'>$comment</td> 
                      </tr>";
    
    }
   
    $sql1="select off_desi from con_officer  where c_id='$officername'";
    $res=$db1->query($sql1);
    $rows1 = $db1->fetch_array();
    $con_officer = $rows1['off_desi'];
       
}


?>
