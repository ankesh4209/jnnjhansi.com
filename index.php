<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");
include("phplib/portal_data.library.php");

//$PAGE_NAME = "Sign In...";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

// Load dynamic website settings and services
$settings = LoadSiteSettings($db);
$home_services = GetHomeServicesHtml($db);
$home_announcements = GetHomeAnnouncementsHtml($db);

GetMayer($db);
GetCommissioner($db);
GetPageContent($db);
GetNotices($db);
GetTenders($db);
GetNews($db);

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/home.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$LEFTBAR      = ReadTemplate("$TEMPLATE_DIR/common/leftbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

// Dynamically build replacement list including all site settings
$replace_keys = array_merge(
    ["LEFTBAR", "TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "RIGHTBAR", "TEMPLATE", "home_services", "home_announcements"],
    array_keys($settings)
);

ReplaceContent($replace_keys);
print $TEMPLATE;
flush();

function GetPageContent($db)
{
   global $pagecontent,$pagetitle;
   $sql="select * from page where status='1' and p_id='1' ";
   $res=$db->query($sql);
   $rows=$db->fetch_array($res);
   $pagecontent=$rows['p_text'];
   $pagetitle=$rows['p_name'];
   $pid=$rows['p_id'];
}

function GetMayer($db)
{
   global $Mayer_Name,$Mayer_Image,$Mayer_Desc,$mid;
   $sql="select * from mayers where status='1'";
   $res=$db->query($sql);
   $rows=$db->fetch_array($res);
   $Mayer_Name=$rows['Mayer_Name'];
   $mid=$rows['Mayer_Id'];
    if($_SERVER['SERVER_NAME']=='localhost')
	{
		$Mayer_Image="<img src='m_images/thumbs/".$rows['Mayer_Photo']."' alt='' width='313' height='172' />";
	}
	else
	{
		$Mayer_Image="<img src='m_images/thumbs/".$rows['Mayer_Photo']."' alt='' width='313' height='172' />";
	}
   $Mayer_Desc=substr($rows['Mayer_Desc'],0,200);
}

function GetCommissioner($db)
{
   global $Comm_Name,$Commissioner_Image,$Commissioner_Desc,$cid;
   $sql="select * from municipal_comm  where status='1'";
   $res=$db->query($sql);
   $rows=$db->fetch_array($res);
   $Comm_Name=$rows['Comm_Name'];
   $cid=$rows['Comm_Id'];
    if($_SERVER['SERVER_NAME']=='localhost')
	{
		$Commissioner_Image="<img src='c_images/thumbs/".$rows['Comm_Photo']."' alt='' width='313' height='172' />";
	}
	else
	{
		$Commissioner_Image="<img src='c_images/thumbs/".$rows['Comm_Photo']."' alt='' width='313' height='172' />";
	}
   
   $Commissioner_Desc=substr($rows['Comm_Desc'],0,200);
}

function GetNews($db)
{
   global $Work_Id,$Work_Name,$Work_Desc,$Work_Photo,$Work_view;
   $sql="select * from jnnworks where Status='1' order by Work_Id DESC";
   $res=$db->query($sql);
    $i=0;
	while($rows = $db->fetch_array())
	{
		$Work_Id=$rows['Work_Id'];
	 
	   $Work_Name=$rows['Work_Name'];
	   if($_SERVER['SERVER_NAME']=='localhost')
		{
			$Work_view.="<div id='pic'><a href='w_images/".$rows['Work_Photo']."' style='color:#000000;' target='_new'><img src='/jnn/w_images/thumbs/".$rows['Work_Photo']."'></a></div>";
		}
		else
		{
			$Work_view.="<div id='pic'><a href='/w_images/".$rows['Work_Photo']."' style='color:#000000;' target='_new'><img src='/w_images/thumbs/".$rows['Work_Photo']."' ></a></div>";
		}
	  $i++;
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
			$notice_view.="<a href='docs/".$rows['Pdf_Name']."' style='color:#e41212;' target='_new'>".$NoticeName."</a>::&nbsp;";
		}
		else
		{
			$notice_view.="<a href='/docs/".$rows['Pdf_Name']."' style='color:#e41212;' target='_new'>".$NoticeName."</a>::&nbsp;";
		}
	  $i++;
	}			
}

function GetTenders($db)
{
   global $Tender_Id,$TenderName,$TenderList,$cid,$tender_view;
   $sql="select * from pdffiles where Status='1' order by Pdf_Id DESC";
   $res=$db->query($sql);
   $i=0;
	while($rows = $db->fetch_array())
	{
		$Tender_Id=$rows['Pdf_Id'];
	 
	   $TenderName=$rows['Pdf_Desc'];
	   if($_SERVER['SERVER_NAME']=='localhost')
		{
			$tender_view.="<li><a href='docs/".$rows['Pdf_Name']."' style='color:#000000;' target='_new'>".$TenderName."</a></li>";
		}
		else
		{
			$tender_view.="<li><a href='/docs/".$rows['Pdf_Name']."' style='color:#000000;' target='_new'>".$TenderName."</a></li>";
		}
	$i++;
	}			
}

?>
