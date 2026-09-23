<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$complainno=$_GET['id'];
Search_comp($db,$complainno);
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/printmail.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();


function Search_comp($db,$complainno)
 {
   global $cid,$name,$summery,$complain_date,$complain_detail,$category,$nature,$tdate;
   
   $sql="select * from complainant where c_id='$complainno'";
   $res=$db->query($sql);
   if($db->num_rows())
   {
     $rows=$db->fetch_array($res);
     $cid=$rows['c_id'];
     $name=$rows['c_name'];
     $summery=$rows['c_detail'];
     $complain_date=date('d-F-Y',$rows['c_date']);
     $city=$rows['c_city'];
     $address=$rows['c_add'];
     $category=$rows['c_category'];
     $nature=$rows['c_nature'];
     
     if($category=='D')
      {
        $tdate=$rows['tdate'];
      }
     else
      {
        $tdate=date('d-F-Y',$rows['tdate']);
      }
     
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
                        <td width='150' height='35' valign='middle'>Disposed Summery:</td>
                        <td height='35' valign='middle' style='font-family:Kruti Dev 011;font-size:20px;'>$disposed_text1</td>
                       </tr>";
      }
     
     $complain_detail="<tr>
                        <td width='150' height='35' valign='middle'>Complaint No :</td>
                        <td height='35' valign='middle'>$cid</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Complainant Name :</td>
                        <td height='35' valign='middle' style='font-family:Kruti Dev 011;font-size:20px;'>$name</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Complaint Date:</td>
                        <td height='35' valign='middle'>$complain_date</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Target Date:</td>
                        <td height='35' valign='middle'>$tdate</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Complaint Category:</td>
                        <td height='35' valign='middle'>$category</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Nature of Complaint :</td>
                        <td height='35' valign='middle'>$nature</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Complaint Details :</td>
                        <td height='35' valign='middle' style='font-family:Kruti Dev 011;font-size:20px;'>$summery</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Address :</td>
                        <td height='35' valign='middle' style='font-family:Kruti Dev 011;font-size:20px;'>$address</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>City :</td>
                        <td height='35' valign='middle'>$city</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Complaint Status:</td>
                        <td height='35' valign='middle'>$status</td>
                       </tr>
                       $disposed_text
                       ";
     
   }
   else
   {
     $complain_detail="<tr>
                        <td>No Record Found</td>
                       </tr>";
   }
 }
?>
