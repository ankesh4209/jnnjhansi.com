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
   $pagecontent = "";
   $sql="select * from tbl_gallary where status='1' order by g_id DESC";
   $res=$db->query($sql);
   if($db->num_rows())
   {
       $pagecontent = "<div class='gallery-responsive-grid' style='display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:18px;'>";
       while($rows = $db->fetch_array())
       {
           $img_src = (!empty($rows['photo_name']) && file_exists('pic/thumb/' . $rows['photo_name'])) 
               ? 'pic/thumb/' . $rows['photo_name'] 
               : ((!empty($rows['photo_name']) && file_exists('pic/' . $rows['photo_name'])) ? 'pic/' . $rows['photo_name'] : 'images/logo.png');
           $full_img = (!empty($rows['photo_name']) && file_exists('pic/thumb/thumb_' . $rows['photo_name'])) 
               ? 'pic/thumb/thumb_' . $rows['photo_name'] 
               : $img_src;

           $pagecontent .= "<div class='gallery-card' style='background:#FAFCFE; border:1px solid var(--border-color); border-radius:var(--radius-md); overflow:hidden; box-shadow:var(--shadow-xs); transition:transform 0.2s, box-shadow 0.2s;'>";
           $pagecontent .= "<a href='" . htmlspecialchars($full_img) . "' target='_blank' style='display:block;'>";
           $pagecontent .= "<img src='" . htmlspecialchars($img_src) . "' alt='Jhansi Nagar Nigam' style='width:100%; height:160px; object-fit:cover; display:block;'>";
           $pagecontent .= "</a></div>";
       }
       $pagecontent .= "</div>";
   }
   else
   {
       $pagecontent = "<p style='color:var(--text-muted); font-size:14px; padding:20px; background:#FAFCFE; border-radius:8px;'>No gallery images currently available.</p>";
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
