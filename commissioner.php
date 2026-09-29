<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

GetNotices($db);

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
if ($cid > 0) {
    $query = "select * from municipal_comm where Comm_Id='$cid'";
} else {
    $query = "select * from municipal_comm where status='1' order by Comm_Id desc limit 1";
}
$db->query($query);
$rows = $db->fetch_array();

$comm_photo = (!empty($rows['Comm_Photo']) && file_exists('c_images/thumbs/' . $rows['Comm_Photo']))
    ? "c_images/thumbs/" . $rows['Comm_Photo']
    : "images/logo.png";
$c_image = "<img src='" . $comm_photo . "' style='max-width:100%; height:auto; border-radius:8px;' alt='Municipal Commissioner'>";

$Comm_Name = !empty($rows['Comm_Name']) ? $rows['Comm_Name'] : "Municipal Commissioner";
$Comm_Desc = !empty($rows['Comm_Desc']) ? $rows['Comm_Desc'] : "Welcome to Jhansi Municipal Corporation official administrative portal.";

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/commissioner.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();



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
