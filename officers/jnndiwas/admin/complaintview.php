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
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());


$cid=$_GET['cid'];
$dis=$_GET['dis'];

complaintviews($db,$db1,$db2,$cid,$dis);
//studentfee($db,$stid);
if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
    
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/complaintview.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();



function complaintviews($db,$db1,$db2,$cid,$dis)
{
   global $cid,$cname,$dis,$disposal_text,$caddress,$ccontno,$c_regno,$cdetail,$nature,$officer,$departname,$cdate,$ccategory
          ,$ctime,$cnewspaper,$cmode,$conofficer,$tdate,$cmodetype,$city;
   
   $sql="select * from complainant where c_id='$cid'";
   $db->query($sql);
   $rows = $db->fetch_array();
   
   $cid=$rows['c_id'];
   $cname=stripslashes($rows['c_name']);
   $caddress=stripslashes($rows['c_add']);
   $contact1=$rows['c_contno'];
   
   $c_regno=stripslashes($rows['c_regno']);
   $cdetail=stripslashes($rows['c_detail']);
   $officername=$rows['officer_name'];
   $departname=stripslashes($rows['d_name']);
   $cdate1=$rows['c_date'];
   $cdate=date('d/m/Y',$cdate1);
   $ccategory=$rows['c_category'];
   $ctime=$rows['c_time'];
   $con_officer=$rows['con_officer'];
   //$cnewspaper=$rows['c_newspaper'];
   $cmode=$rows['c_mode'];
   $nature=$rows['c_nature'];
   $t_date=$rows['tdate'];
   $city=$rows['c_city'];
   
   //-------------------------- city ----------------------
       $city1=$rows['c_city'];
       $sql3="select city_name from city where city_id='$city1'";
       $res3=$db1->query($sql3);
       $rows3=$db1->fetch_array($res3);
       $city=$rows3['city_name'];
       
     //-------------------------- nature -------------------
       $nature1=$rows['c_nature'];
       $sql4="select nature_name from nature where nature_id='$nature1'";
       $res4=$db1->query($sql4);
       $rows4=$db1->fetch_array($res4);
       $nature=$rows4['nature_name'];
       
   if($ccategory=='D')
   {
     $tdate="No Time Bound";
   }
   else
   {
     $tdate=date('d-m-Y',$t_date);
   }
   
   if($cmode=='Phone')
       {
         $cmodetype="<tr>
                      <td>Mobile/Telephones :</td>
                      <td>$contact1</td>
                     </tr>";
       }
      elseif($cmode=='Fax')
       {
         $cmodetype="<tr>
                      <td>Fax:</td>
                      <td>$fax</td>
                     </tr>";
       }
      elseif($cmode=='Newspaper')
       {
         $newspaperdate=$rows['c_ndate'];
         $newspaperdate=date('d-m-Y',$newspaperdate);
         $cmodetype="<tr>
                      <td>Newspaper Name:</td>
                      <td>$newspapername  &nbsp;&nbsp;&nbsp;Newspaper Date : &nbsp;&nbsp; $newspaperdate</td>
                     </tr>";
       }
      elseif($cmode=='Television')
       {
         $televisiondate=$rows['c_tdate'];
         $televisiondate=date('d-m-Y',$televisiondate);
         $cmodetype="<tr>
                      <td>Television Name:</td>
                      <td>$televisionname &nbsp;&nbsp;&nbsp;Television Date : &nbsp;&nbsp; $televisiondate</td>
                     </tr>";
       }
      else
       {
         $cmodetype="";
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
                        <td width='200'>Disposed Comment:</td>  
                        <td align='left' style='font-family:Kruti Dev 011;font-size:15px;'>$comment</td> 
                      </tr>";
    
    }
   
    $sql1="select off_desi from officer where o_id='$officername'";
    $res=$db1->query($sql1);
    $rows1 = $db1->fetch_array();
    $officer = $rows1['off_desi'];
    
    $sql2="select off_desi from con_officer where c_id='$con_officer'";
    $res2=$db2->query($sql2);
    $rows2 = $db2->fetch_array();
    $conofficer = $rows2['off_desi'];
       
}


?>
