<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


GetPageContent($db);
GetNotices($db);

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/gallery.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function Getpagecontent($db)
{
   
   global $pagecontent;
   $sql="select * from tbl_gallary  where status='1'";
   $res=$db->query($sql);
    if($db->num_rows())
	{
            
			$pagecontent.="<table cellspacing='0' cellpadding='0' border='0' width='100%' ><tr>";
			$i=0;
			while($rows = $db->fetch_array())
			{
			 
			    $pagecontent.="<td width='30%' align='center'> <li><a href='pic/thumb/thumb_".$rows['photo_name']."' title='gallery' ><img src='pic/thumb/".$rows['photo_name']."' style='max-width:180px; height:120px; object-fit:cover; border-radius:8px;' ></a></li></td>";
			   $i++;
			   if($i==4)
				{
					$pagecontent.="</tr><tr><td style='height:20px;'>&nbsp;</td></tr><tr>";
					$i=0;
				}
			}
			$pagecontent.="</tr></table>";
			 
	}
	else
	{
		$pagecontent.="No Image Available.";
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
