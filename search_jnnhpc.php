<?php	
include("jnnhpc/config/data.config.php");
include("jnnhpc/phplib/functions.library.php");
include("jnnhpc/phplib/class.database.php");
include("jnnhpc/phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

GetNotices($db);

if($_POST['submit']!="")
{
 $complainno=$_POST['cno'];
 $cdate1=$_POST['tdate'];
 $cdate1=explode("/",$cdate1);
 $cdate=mktime(0,0,0,$cdate1['1'],$cdate1['0'],$cdate1['2']);
 $contactno=$_POST['contactno'];
 $cname=$_POST['cname'];
 
 Search_comp($db,$complainno,$cdate,$contactno,$cname);
 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/searchResult_jnnhpc.html");
}
else
{
 $PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/search_jnnhpc.html");
}
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
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
    
   $sql="select * from complainant where 1=1 $complainno1 $contactno1 $cname1 $cdate1 order by c_id desc";
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
                        <td><input type='button' name='print' value='View' onclick=\" openPopup('jnnhpc_print.php?id=$cid');\"></td>
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
