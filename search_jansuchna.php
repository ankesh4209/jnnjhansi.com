<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

GetNotices($db);

if($_POST['submit']!="")
{
 $complainno=$_POST['regno'];
 $phone=$_POST['phone'];
 $rdate=$_POST['rdate'];
 
 Search_comp($db,$complainno,$phone,$rdate);
 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/searchResult_jansuchna.html");
}
else
{
 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/search_jansuchna.html");
}

$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function Search_comp($db,$complainno,$phone,$rdate)
 {
   global $cid,$name,$complainno,$summery,$complain_date,$complain_detail;
   
   if($complainno!="")
     $q_compl=" and a_rno='$complainno'";
   else
     $q_compl="";
     
   if($phone!="")
     $q_phone="and a_contactno='$phone'";
   else
     $q_phone="";
   
   if($rdate!="")
    {
     $rdate=explode('/',$rdate);
     $rdate=mktime(0,0,0,$rdate[1],$rdate[0],$rdate[2]);
     $q_date="and a_rdate='$rdate'";
    }
   else
    {
     $q_date="";
    }
    
   $sql="select * from tbl_automation where 1=1 $q_compl $q_phone $rdate";
   $res=$db->query($sql);
   if($db->num_rows())
   {
     while($rows=$db->fetch_array($res))
     {
      $aid=$rows['a_id'];
      $rid=$rows['a_rno'];
      $name=$rows['a_name'];
      $fname=$rows['a_fname'];
      $complain_date=date('d-F-Y',$rows['a_rdate']);
     
      $complain_status=$rows['app_status'];
     
       if($complain_status==1)
        {
          $status="Pending";
          $print_status="";
        }
       else if($complain_status==2)
        {
          $status="Complete";
          $print_status="<a href=\"javascript: void(0)\" onclick=\"window.open('print_complete.php?aid=$aid','windowname1','width=650, height=550,scrollbars=yes'); return false;\">Print</a>";
        }
      
     $complain_detail.="<tr>
                        <td>$rid</td>
                        <td style='font-family:Kruti Dev 011;font-size:20px;'>$name</td>
                        <td style='font-family:Kruti Dev 011;font-size:20px;'>$fname</td>
                        <td>$complain_date</td>
                        <td>$status</td>
                        <td>$print_status</td>
                        
                       </tr>";
     }
   }
   else
   {
     $complain_detail="<tr>
                        <td colspan='5' align='center'>No Record Found</td>
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
