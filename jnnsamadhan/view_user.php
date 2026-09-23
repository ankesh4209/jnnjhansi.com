<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/view_user.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


function Search_comp($db,$complainno,$cdate,$contactno,$cname)
 {
   global $cid,$name,$summery,$complain_date,$complain_detail;
   
   if($complainno!="")
    {
      $complainno1=" and c_id='$complainno'";
    }
    
   if($contactno!="")
    {
      $contactno1="and c_contno='$contactno'";
    }
   
   if($cname!="")
    {
      $cname1="and c_name='$cname'";
    }
    
    if($cdate!="")
    {
      $cdate1="and c_date='$cdate'";
    }
    
   $sql="select * from complainant where 1=1 $complainno1 $contactno1 $cname1 $cdate1";
   $res=$db->query($sql);
   if($db->num_rows())
   {
     while($rows=$db->fetch_array($res))
     {
     $cid=$rows['c_id'];
     $name=$rows['c_name'];
     $summery=$rows['c_detail'];
     $complain_date=date('d-F-Y',$rows['c_date']);
     
     $complain_status=$rows['status'];
     
     if($complain_status==0)
      {
        $status="Pending";
        $disposed_text="";
      }
     else
      {
        $status="Disposed";
        $disposed_text1=$rows['disposaltext'];
        $disposed_text="<tr>
                        <td width='150'>Disposed Summery:</td>
                        <td style='font-family:Kruti Dev 011;font-size:20px;'>$disposed_text1</td>
                       </tr>";
      }
      
     $complain_detail.="<tr>
                        <td>$cid</td>
                        <td style='font-family:Kruti Dev 011;font-size:20px;'>$name</td>
                        <td>$complain_date</td>
                        <td>$status</td>
                        <td><input type='button' name='print' value='View' onclick=\" openPopup('print.php?id=$cid');\"></td>
                       </tr>";
     }
   }
   else
   {
     $complain_detail="<tr>
                        <td>No Record Found</td>
                       </tr>";
   }
 }
?>
