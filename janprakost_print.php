<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

GetNotices($db);

$complainno=$_GET['id'];
Search_comp($db,$db1,$complainno);
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/janprakost_print.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();


function Search_comp($db,$db1,$complainno)
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
     //$city=$rows['c_city'];
     $address=$rows['c_add'];
     $category=$rows['c_category'];
     //$nature=$rows['c_nature'];
     
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
     
     //------------------------------------------------ mode ----------------------------------------
     $cmode=$rows['c_mode'];
     $ccontact=$rows['c_contno'];
     $fax=$rows['fax'];
     
     
     if($cmode=='Phone')
       {
         $cmodetype="<tr><td>Mobile/Telephones :</td><td>$ccontact</td></tr>";
       }
      elseif($cmode=='Fax')
       {
         $cmodetype="<tr><td>Fax:</td><td>$fax</td></tr>";
       }
      elseif($cmode=='Newspaper')
       {
         $newspaperdate=$rows['c_ndate'];
         $newspaperdate=date('d-m-Y',$newspaperdate);
         $newspapername=$rows['c_newspapername'];
         $cmodetype="<tr><td>Newspaper Name:</td><td>$newspapername  &nbsp;&nbsp;&nbsp;Newspaper Date : &nbsp;&nbsp; $newspaperdate</td></tr>";
       }
      elseif($cmode=='Television')
       {
         $televisiondate=$rows['c_tdate'];
         $televisiondate=date('d-m-Y',$televisiondate);
         $televisionname=$rows['c_televisionname'];
         $cmodetype="<tr><td>Television Name:</td><td>$televisionname &nbsp;&nbsp;&nbsp;Television Date : &nbsp;&nbsp; $televisiondate</td></tr>";
       }
      else
       {
         $cmodetype="";
       }
     //------------------------------------------------------------------------------------------------  
       
     $complain_detail="<tr>
                        <td width='150' height='35' valign='middle'>Complaint No :</td>
                        <td height='35' valign='middle'>$cid</td>
                       </tr>
                       <tr>
                        <td height='35' valign='middle'>Complainant Name :</td>
                        <td height='35' valign='middle' style='font-family:Kruti Dev 011;font-size:20px;'>$name</td>
                       </tr>
                       $cmodetype
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

function GetNotices($db)
{
   global $Notice_Id,$NoticeName,$NoticeList,$cid,$notice_view;
   $sql="select * from notice where Status='1' order by Pdf_Id DESC";
   $res=$db->query($sql);
    $i=0;
	while($rows = $db->fetch_array())
	{
		$Notice_Id=$rows['Pdf_Id'];
	 
	   $NoticeName=$rows['Pdf_Desc'];

	   if($_SERVER['SERVER_NAME']=='localhost')
		{
			$notice_view.="<a href='docs/".$rows['Pdf_Name']."' style='color:#000000;' target='_new'>".$NoticeName."</a>::&nbsp;";
		}
		else
		{
			$notice_view.="<a href='/docs/".$rows['Pdf_Name']."' style='color:#000000;' target='_new'>".$NoticeName."</a>::&nbsp;";
		}
	  $i++;
	}			
}
?>
